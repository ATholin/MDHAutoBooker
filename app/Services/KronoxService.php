<?php

namespace App\Services;

use App\Booking;
use App\KronoxCredentials;
use App\ScheduledBooking;
use Carbon\Carbon;
use DOMDocument;
use DOMNode;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie as CookieParser;
use Illuminate\Support\Str;

class KronoxService
{
    /**
     * @var array
     */
    protected array $intervals = [
        [
            'interval' => 0,
            'time' => '08:15 - 10:00',
        ],
        [
            'interval' => 1,
            'time' => '10:15 - 12:00',
        ],
        [
            'interval' => 2,
            'time' => '12:15 - 14:00',
        ],
        [
            'interval' => 3,
            'time' => '14:15 - 16:00',
        ],
        [
            'interval' => 4,
            'time' => '16:15 - 18:00',
        ],
        [
            'interval' => 5,
            'time' => '18:15 - 20:00',
        ],
    ];
    /**
     * @var string[]
     */
    protected array $rooms = [
        'R1-013',
        'R1-014',
        'R1-016',
        'R1-017',
        'R1-018',
        'R1-028',
        'R1-029',
        'R1-030',
        'R2-001',
        'R2-003',
        'R2-012',
        'R2-013',
        'R2-014',
        'R2-031',
        'R2-032',
        'R2-042',
        'R2-089',
        'R2-090',
        'R2-091',
        'R2-092',
        'U2-009',
        'U2-010',
        'U2-011',
        'U2-012',
        'U2-043',
        'U2-044',
        'U2-260',
        'U2-261',
        'U2-263',
        'U2-264',
        'U2-265',
        'U2-267',
        'U2-269',
        'U2-271',
        'U2-273',
    ];
    private Client $client;

    public function __construct()
    {
        $this->client = $this->getClient();
    }

    protected function getClient(): Client
    {
        return new Client([
            'base_uri' => config('kronox.base_uri'),
        ]);
    }

    /**
     * @param string $username
     * @param string $password
     * @return string
     */
    public function login(string $username, string $password): string
    {
        $session = $this->getInitialSession();

        $cookieJar = CookieJar::fromArray([
            'JSESSIONID' => $session,
        ], $this->getCookieDomain());

        $response = $this->client->post('login_do.jsp', [
            'Content-type' => 'application/x-www-form-urlencoded',
            'form_params' => [
                'username' => $username,
                'password' => $password,
            ],
            'cookies' => $cookieJar,
        ]);

        return $session;
    }

    protected function getInitialSession(): string
    {
        $response = $this->client->get('/');

        return CookieParser::fromString($response->getHeader('Set-Cookie')[0])->getValue();
    }

    protected function getCookieDomain(): string
    {
        return Str::of(config('kronox.base_uri'))->replace(['https://', 'http://'], '');
    }

    public function poll(string $JSESSIONID): bool
    {
        $cookieJar = CookieJar::fromArray([
            'JSESSIONID' => $JSESSIONID,
        ], $this->getCookieDomain());

        return $this->client->get('/', ['cookies' => $cookieJar])->getBody()->getContents() === 'OK';
    }

    public function book(ScheduledBooking $booking)
    {
        $cookieJar = CookieJar::fromArray([
            'JSESSIONID' => $booking->credentials->session,
        ], $this->getCookieDomain());

        return $this->client->get('/ajax/ajax_resursbokning.jsp', [
            'query' => [
                'op' => 'boka',
                'datum' => $booking->date->format('y-m-d'),
                'id' => $booking->room,
                'typ' => 'RESURSER_LOKALER',
                'intervall' => $booking->interval,
                'moment' => $booking->message ?? ' ',
                'flik' => 'FLIK_0001',
            ],
            'cookies' => $cookieJar,
        ])->getBody()->getContents();
    }

    public function unBook(string $booker, string $id)
    {
        $credentials = KronoxCredentials::whereUsername($booker)->first();

        if (! $credentials) {
            return false;
        }

        $cookieJar = CookieJar::fromArray([
            'JSESSIONID' => $credentials->session,
        ], $this->getCookieDomain());

        return $this->client->get('/ajax/ajax_resursbokning.jsp', [
            'query' => [
                'op' => 'avboka',
                'bokningsId' => $id,
            ],
            'cookies' => $cookieJar,
        ])->getBody()->getContents();
    }

    public function bookings(string $JSESSIONID)
    {
        $url = '/minaresursbokningar.jsp?flik=FLIK_0001';
        $url = $url.'&datum='.substr(now(), 2, 8);

        $cookieJar = CookieJar::fromArray([
            'JSESSIONID' => $JSESSIONID,
        ], $this->getCookieDomain());

        $html = $this->client->get($url, [
            'cookies' => $cookieJar,
        ]);

        if (empty($html)) {
            return [];
        }

        $dom = new DOMDocument;
        $dom->loadHTML($html->getBody()->getContents());

        $bookings = [];
        foreach ($dom->getElementsByTagName('div') as $div) {
            $styles = 'padding:5px;margin-bottom:10px;margin-top:10px;border:1px solid #E6E7E6;background:#FFF;';
            if ($div->getAttribute('style') == $styles) {
                $divInnerHtml = $this->DOMinnerHTML($div->getElementsByTagName('div')->item(0));

                $temp = new Booking;
                $temp->bookingID = substr($div->getAttribute('id'), 5, 29);

                $date = $this->DOMinnerHTML($div->getElementsByTagName('a')->item(0));
                $time = substr($divInnerHtml, 72, 13);

                $temp->interval = $this->timeToInterval($time);

                $temp->date = $date;
                $temp->time = $time;

                [$startTime, $endTime] = explode(' - ', $time);

//                $temp->start = Carbon::parse("{$date} {$startTime}");
                $endDate = Carbon::parse("{$date} {$endTime}");

                $temp->booker = substr($divInnerHtml, 90, 8);
                $temp->room = substr($divInnerHtml, 100, 6);
                $temp->message = $this->DOMinnerHTML($div->getElementsByTagName('small')->item(0));

                if ($endDate->gt(now())) {
                    $bookings[] = $temp;
                }
            }
        }

        return $bookings;
    }

    protected function DOMinnerHTML(DOMNode $element)
    {
        $innerHTML = '';
        $children = $element->childNodes;

        foreach ($children as $child) {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }

    public function timeToInterval(string $time)
    {
        return collect($this->intervals)->firstWhere('time', $time)['interval'];
    }

    public function all(string $JSESSIONID, Carbon $date = null)
    {
        if (! $date) {
            $date = now();
        }

        $url = "/ajax/ajax_resursbokning.jsp?op=hamtaBokningar&flik=FLIK_0001&datum={$date->format('y-m-d')}";

        $cookieJar = CookieJar::fromArray([
            'JSESSIONID' => $JSESSIONID,
        ], $this->getCookieDomain());

        $html = $this->client->get($url, [
            'cookies' => $cookieJar,
        ])->getBody()->getContents();

        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $dom = new DOMDocument;
        $dom->loadHTML($html);

        $rows = [];
        $headerRow = $dom->getElementsByTagName('tr')->item(0);
        $dom->getElementsByTagName('tr')->item(0)->parentNode->removeChild($headerRow);

        foreach ($dom->getElementsByTagName('tr') as $tableRow) {
            $row = [];

            foreach ($tableRow->getElementsByTagName('td') as $cell) {
                if ($cell->getAttribute('class') == 'grupprum-kolumn') {
                    $text = self::DOMinnerHTML($cell->getElementsByTagName('b')->item(0));
                    $tooltip = self::DOMinnerHTML($cell->getElementsByTagName('small')->item(0));
                    $row[] = ['text' => $text, 'tooltip' => $tooltip];
                } elseif (strpos($cell->getAttribute('class'), 'grupprum-upptagen') !== false) {
                    $text = trim(html_entity_decode(self::DOMinnerHTML($cell->getElementsByTagName('center')->item(0))), " \t\n\r\0\x0B\xC2\xA0");
                    //$text = substr($item, 0, -3); //Don't ask, magic!
                    $tooltip = $cell->getAttribute('title');
                    $row[] = ['text' => $text, 'tooltip' => $tooltip];
                } elseif ($cell->getAttribute('class') == 'grupprum-ledig grupprum-kolumn') {
                    $row[] = ['text' => 'Free'];
                } elseif ($cell->getAttribute('class') == 'grupprum-passerad grupprum-kolumn') {
                    $row[] = ['text' => ''];
                }
            }
            $rows[] = $row;
        }

        $rows = array_reverse($rows); // Get U2 rooms on top!

        return $rows;
    }

    public function isRoomValid(string $room)
    {
        return in_array(strtoupper($room), $this->rooms);
    }

    public function getRooms()
    {
        return $this->rooms;
    }

    public function getIntervals()
    {
        return $this->intervals;
    }

    public function intervalToTime(int $interval)
    {
        return collect($this->intervals)->firstWhere('interval', $interval)['time'];
    }

    protected function carbonToInterval(Carbon $start, Carbon $end)
    {
        $tStart = $start->format('H:i');
        $tEnd = $end->format('H:i');
        $time = "{$tStart} - {$tEnd}";

        return collect($this->intervals)->firstWhere('time', $time)['interval'];
    }
}
