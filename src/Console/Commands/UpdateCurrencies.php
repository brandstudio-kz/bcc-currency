<?php

namespace BrandStudio\Currency\Console\Commands;

use Illuminate\Console\Command;

class UpdateCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates currencies';

    protected $url;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->url = config('currency.url');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $xml = file_get_contents($this->url);
        $xml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $currencies = json_decode($json,TRUE)['channel']['item'];

        foreach($currencies as $currency) {
            $currency = config('currency.currency_class')::updateOrCreate(
                ['title' => mb_strtolower($currency['title'])],
                [
                    'pub_date'      => is_array($currency['pubDate']) ? null : $currency['pubDate'],
                    'description'   => is_array($currency['description']) ? null : $currency['description'],
                    'quant'         => is_array($currency['quant']) ? null : $currency['quant'],
                    'value'         => (is_array($currency['description']) || is_array($currency['quant'])) ? null : ($currency['description']/$currency['quant']),
                    'index'         => is_array($currency['index']) ? null : mb_strtoupper($currency['index']),
                    'change'        => is_array($currency['change']) ? null : str_replace('+', '', $currency['change'])
                ]
            );
        }

    }
}
