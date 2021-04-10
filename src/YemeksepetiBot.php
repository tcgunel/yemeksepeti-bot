<?php

namespace TCGunel\YemeksepetiBot;

use Illuminate\Support\Facades\Http;
use TCGunel\YemeksepetiBot\Constants\ViewMode;
use TCGunel\YemeksepetiBot\Models\Category;
use TCGunel\YemeksepetiBot\Models\Product;

class YemeksepetiBot extends YemeksepetiBotClient
{
    use HandleErrors;

    protected $html_content;

    protected $categories;

    protected $view_mode;

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * YemeksepetiBot constructor.
     * @param Http|null http_client
     * @param string $url
     */
    public function __construct($http_client, string $url)
    {
        parent::__construct($http_client, $url);
    }

    public function run()
    {
        $this->setHtmlContent()->setViewMode($this->html_content)->parseHtmlContent();
    }

    public function setHtmlContent(): YemeksepetiBot
    {
        $response = $this->http_client::get($this->url);

        if ($response->successful()) {

            $this->html_content = $response->body();

        }

        return $this;
    }

    public function getHtmlContent(): string
    {
        return $this->html_content;
    }

    public function parseHtmlContent(): void
    {
        preg_match_all('/restaurantDetailBox (.*?)<\/ul>/', $this->html_content, $matches);

        if (!empty($matches)) {

            $matches = $matches[0];

            foreach ($matches as $match) {

                if (strpos($match, 'favFoods') !== false) {

                    continue;

                }

                $category_name = $this->findCategoryNameFromHtml($match);

                if ($category_name) {

                    $category = new Category([
                        "name"     => $category_name,
                        "products" => []
                    ]);

                    $category->products = $this->parseProductsFromCategoryHtml($match);

                    $this->categories[] = $category;

                }

            }

        }
    }

    public function parseProductsFromCategoryHtml($category_block_html): array
    {
        $products = [];

        preg_match_all("/<li>(.*?)<\/li>/", $category_block_html, $matches);

        if (!empty($matches)) {

            $matches = $matches[0];

            foreach ($matches as $match) {

                $product_id   = $this->findProductIdFromHtml($match);
                $product_name = $this->findProductNameFromHtml($match);
                $description  = $this->findProductDescriptionFromHtml($match);
                $image        = $this->findProductImagePathFromHtml($match);
                $prices       = $this->findProductPriceFromHtml($match);
                $stock        = $this->findProductStockFromHtml($match);

                $products[] = new Product([
                    "id"           => $product_id,
                    "title"        => $product_name,
                    "description"  => $description,
                    "image"        => $image,
                    "stock"        => $stock,
                    "price"        => $this->fixPrice($prices["price"]),
                    "normal_price" => isset($prices["normal_price"]) ? $this->fixPrice($prices["normal_price"]) : null,
                ]);

            }

        }

        return $products;
    }

    public function findCategoryNameFromHtml($html)
    {
        preg_match('/<h2><b>(.*)<\/b><\/h2>/', $html, $matches);

        if (is_array($matches) && count($matches) === 2) {

            return html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');

        }

        return false;
    }

    public function findProductNameFromHtml($html)
    {
        preg_match('/data-productname=\"(.*?)\"/', $html, $matches);

        if (ViewMode::GRID_VIEW){

            preg_match('/<a.*>(.*?)<\/a>/', $html, $matches);

        }

        if (empty($matches)) {

            preg_match('/class="getProductDetail" data-product-id=".*?" data-category-name=".*?" data-top-sold-product="false">(.*?)<\/a>/', $html, $matches);

        }

        if (empty($matches)){

            preg_match('/data-top-sold-product="false">([^\s].*?)<\/(?:a|strong)> <(?:\/div|span class="orderWarning")>/', $html, $matches);
        }

        if (is_array($matches) && count($matches) === 2) {

            return html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');

        }

        return false;
    }

    public function findProductPriceFromHtml($html): array
    {
        preg_match('/"(?:listed-price|listedPrice)">(.*?)<\//', $html, $listed_price_matches);

        preg_match('/"(?:price|newPrice)">(.*?)<\//', $html, $price_matches);

        $listed_price = $price = null;

        $prices = [];

        if (is_array($listed_price_matches) && count($listed_price_matches) === 2) {

            if (isset($listed_price_matches[1])) {

                $listed_price = $listed_price_matches[1];

            }

        }

        if (is_array($price_matches) && count($price_matches) === 2) {

            if (isset($price_matches[1])) {

                $price = $price_matches[1];

            }

        }

        if ($listed_price !== null) {

            $prices["normal_price"] = $listed_price;

        }

        $prices["price"] = $price;

        return $prices;
    }

    public function findProductDescriptionFromHtml($html)
    {
        if ($this->view_mode === ViewMode::LIST_VIEW) {

            preg_match('/"product-desc">(.*?)<\/div>/', $html, $matches);

        } else {

            preg_match('/<p>(.*?)<\/p>/', $html, $matches);

        }

        if (is_array($matches) && count($matches) === 2) {

            $matches[1] = str_replace("\\r", "", $matches[1]);

            return html_entity_decode(trim($matches[1]), ENT_QUOTES, 'UTF-8');

        }

        return false;
    }

    public function findProductIdFromHtml($html)
    {
        preg_match('/data-product-id="([\w\d\-]+)"/', $html, $matches);

        if (is_array($matches) && count($matches) === 2) {

            return $matches[1];

        }

        return false;
    }

    public function findProductStockFromHtml($html): bool
    {
        preg_match('/<span class="orderWarning">.*?<\/span>/', $html, $matches);

        if (is_array($matches) && count($matches) === 1) {

            return false;

        }

        return true;
    }

    public function findProductImagePathFromHtml($html)
    {
        preg_match('/data-(?:imagepath|src)="(.*?)"/', $html, $matches);

        if (is_array($matches) && count($matches) === 2) {

            return $matches[1];

        }

        return false;
    }

    public function fixPrice($string): float
    {
        $string = preg_replace("/[^\d,]+/", "", $string);

        return (float)str_replace(",", ".", $string);
    }

    /**
     * @return mixed
     */
    public function getViewMode()
    {
        return $this->view_mode;
    }

    /**
     * @param string $html_content
     * @return YemeksepetiBot
     */
    public function setViewMode(string $html_content): YemeksepetiBot
    {
        $this->view_mode = strpos($html_content, "gallery-view") !== false ? ViewMode::GRID_VIEW : ViewMode::LIST_VIEW;

        return $this;
    }
}
