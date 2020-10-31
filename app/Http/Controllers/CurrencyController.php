<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Currency;
use App\Models\CurrencyData;
use Carbon\Carbon;

class CurrencyController extends Controller
{
    public function show()
    {
        $this->updateCurrenciesData();

        $currenciesData = Currency::with('details')->get();
        $data = CurrencyData::where('type', '=', 'buy')->get();

        $updates = $data->sortBy('update')->pluck('update');
        $labels = $updates->map(function ($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->unique()->all();

        $labels = json_encode(array_values($labels));

        $dataSets = [];
        $i = 0;
        foreach ($currenciesData as $item) {
            $dataSets[] = [
                "label" => $item->code,
                "data" => array_values($item->details()->pluck('value')->toArray()),
                "borderColor" => $this->prepareColor($i),
                "borderWidth" => 2,
                "fill" => false
            ];
            $i++;
        }

        return view('currencies.show', compact('labels', 'dataSets'));
    }

    public function updateCurrenciesData()
    {
        foreach ($this->getCurrenciesData() as $item) {
            $data = $item['data'];
            $currency = Currency::where('code', '=', $data['code'])
                                ->first() ?: new Currency();

            $currency->name = $data['name'];
            $currency->qty = $data['qty'];
            $currency->code = $data['code'];
            $currency->buy = $data['buy'] ?? null;
            $currency->sale = $data['sale'] ?? null;
            $currency->save();

            $currencyData = CurrencyData::where('currency_id', '=', $currency->id)
                        ->where('type', '=', 'buy')
                        ->where('update', '=', $this->prepareUpdateDate($item['update']))
                        ->first() ?: new CurrencyData();
            $currencyData->currency_id = $currency->id;
            $currencyData->type = 'buy';
            $currencyData->value = $data['buy'];
            $currencyData->update = $this->prepareUpdateDate($item['update']);
            $currencyData->save();

            $currencyData = CurrencyData::where('currency_id', '=', $currency->id)
                        ->where('type', '=', 'sale')
                        ->where('update', '=', $this->prepareUpdateDate($item['update']))
                        ->first() ?: new CurrencyData();
            $currencyData->currency_id = $currency->id;
            $currencyData->type = 'sale';
            $currencyData->value = $data['sale'];
            $currencyData->update = $this->prepareUpdateDate($item['update']);
            $currencyData->save();
        }
    }

    public function getCurrenciesData()
    {
        $url = 'http://www.kubisa.pl/aktualne-notowania-w-kantorach';
        $html = file_get_contents($url);

        $crawler = new Crawler($html);
        $tableTr = $crawler->filter('#yoo-zoo table')->eq(1)->filter('tr');

        $map = [
            1 => 'name',
            2 => 'qty',
            3 => 'code',
            4 => 'buy',
            5 => 'sale'
        ];

        $currencies = [];
        $tableTr->each(function (Crawler $tr, $j) use (&$currencies, &$map) {
            $tdCount = $tr->filter('td')->count();
            if ($tdCount == 5) {
                $tr->filter('td')->each(function (Crawler $td, $i) use (&$currencies, $j, &$map) {
                    if (trim($td->text())) {
                        if ($i == 1) {
                            $currencies[$j]['data'][$map[$i]] = trim($td->text());
                        } else if ($i == 2) {
                            $exp = explode(' ', trim($td->text()));
                            $currencies[$j]['data'][$map[$i]] = $exp[0];
                            $currencies[$j]['data'][$map[++$i]] = $exp[1];
                        } else {
                            ++$i;
                            $currencies[$j]['data'][$map[$i]] = (trim($td->text()) == 'BRAK' ? null : trim($td->text()));
                        }
                    }
                });
            } else {
                if ($tr->filter('td')->text() != null) {
                    $currencies[$j - 1]['update'] = trim(explode('cen: ', $tr->filter('td')->text())[1]);
                }
            }

        });

        return array_values($currencies);
    }

    public function prepareUpdateDate($date)
    {
        $monthMap = [
            'styczeń'       => '01',
            'luty'          => '02',
            'marzec'        => '03',
            'kwiecień'      => '04',
            'maj'           => '05',
            'czerwiec'      => '06',
            'lipiec'        => '07',
            'sierpień'      => '08',
            'wrzesień'      => '09',
            'październik'   => '10',
            'listopad'      => '11',
            'grudzień'      => '12'
        ];

        $explode = explode(', ', $date);
        $explodeDate = explode(' ', $explode[1]);

        $time = $explode[0];
        $day = $explodeDate[0];
        $month = $monthMap[$explodeDate[1]];
        $year = str_replace('r.', '', $explodeDate[2]);

        return $year . '-' . $month . '-' . $day . ' ' . $time;
    }

    public function prepareColor($i)
    {
        $colors = [
            0 => 'red',
            1 => 'blue',
            2 => 'green',
            3 => 'salmon',
            4 => 'gold',
            5 => 'yellow',
            6 => 'darkgreen',
            7 => 'springgreen',
            8 => 'cyan',
            9 => 'deepskyblue',
            10 => 'mediumslateblue',
            11 => 'fuchsia',
            12 => 'purple',
            13 => 'deeppink',
            14 => 'brown',
            15 => 'chocolate',
            16 => 'lightslategray',
            17 => 'black',
            18 => 'mistyrose',
            19 => 'coral',
            20 => 'olivedrab'
        ];

        return $colors[$i];
    }
}
