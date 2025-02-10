<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublicLeaderboardsController extends Controller
{
    public function __invoke(Request $request, string $username): View
    {
        $client = new Client();
        try {
            $res = $client->get("https://twitch.aikki.pl/bridge/$username/leaderboards.json");
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }

        $leaderboards = [];
        $data = json_decode($res->getBody(), true);

        if (!empty($data['first'])) {
            usort($data['first'], function($a, $b) {
                return $a['count'] < $b['count'];
            });

            $leaderboards[] = [
                'title' => 'Ranking nagrody "Pierwszy"',
                'data' => array_map(function($item) {
                    return [
                        'name' => $item['name'],
                        'value' => $item['count'],
                    ];
                }, $data['first']),
            ];
        }

        if (!empty($data['fishing'])) {
            usort($data['fishing'], function($a, $b) {
                return $a['gold'] < $b['gold'];
            });

            $leaderboards[] = [
                'title' => 'Ranking OKW "Rybak" wg ilości złota',
                'data' => array_map(function($item) {
                    return [
                        'name' => $item['displayName'],
                        'value' => $item['gold'] . 'g',
                    ];
                }, $data['fishing']),
            ];

            usort($data['fishing'], function($a, $b) {
                return $a['totalCasts'] < $b['totalCasts'];
            });

            $leaderboards[] = [
                'title' => 'Ranking OKW "Rybak" wg liczby połowów',
                'data' => array_map(function($item) {
                    return [
                        'name' => $item['displayName'],
                        'value' => $item['totalCasts'],
                    ];
                }, $data['fishing']),
            ];

            usort($data['fishing'], function($a, $b) {
                return $a['gold'] / $a['totalCasts'] < $b['gold'] / $b['totalCasts'];
            });

            $fishingAvg = array_filter($data['fishing'], function($item) {
                return $item['totalCasts'] >= 10;
            });

            $leaderboards[] = [
                'title' => 'Ranking OKW "Rybak" wg średniej zdobyczy *',
                'data' => array_map(function($item) {
                    return [
                        'name' => $item['displayName'],
                        'value' => number_format($item['gold'] / $item['totalCasts'], 2) . 'g',
                    ];
                }, $fishingAvg),
                'footnote' => '* powyżej 10 połowów',
            ];
        }

        return view('public.leaderboards', [
            'leaderboards' => $leaderboards,
        ]);
    }
}
