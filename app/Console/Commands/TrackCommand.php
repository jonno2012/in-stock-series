<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class TrackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track all product stock';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Product::all()
            ->tap(fn($products) => $this->output->progressStart($products->count()))
            ->each(function ($product) {
                $product->track();

                $this->output->progressAdvance();
            });

        $this->showResults();
    }

    protected function showResults(): void
    {
        $this->output->progressFinish();

        $data = Product::query()
            ->leftJoin('stock', 'stock.product_id', '=', 'products.id')
            ->get($this->keys());

        $splitKeys = array_map(function ($value) {
            return str_replace('_', ' ', $value);
        }, $this->keys());

        $this->table(
            array_map('ucwords', $splitKeys),
            $data
        );
    }

    protected function keys(): array
    {
        return ['name', 'price', 'url', 'in_stock'];
    }
}
