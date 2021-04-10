<?php

namespace TCGunel\YemeksepetiBot\Tests\Unit;

use TCGunel\YemeksepetiBot\Models\Category;
use TCGunel\YemeksepetiBot\Models\Product;
use TCGunel\YemeksepetiBot\Tests\TestCase;

class YemeksepetiBotListViewTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->html_content = file_get_contents(__DIR__ . "/../ListViewExampleHtmlContent.html");

        $this->bot->setViewMode($this->html_content);
    }

    public function test_can_parse_html_content()
    {
        $this->bot->run();

        $this->assertContainsOnlyInstancesOf(Category::class, $this->bot->getCategories());

        $this->assertContainsOnlyInstancesOf(Product::class, $this->bot->getCategories()[0]->products);
    }

    public function test_can_find_product_name_from_html()
    {
        $html_without_product_photo = "<li> <div class=\"product\"> <div class=\"product-info\"> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"8921d366-c52c-41a0-bd3d-a9c87d4d6389\" data-category-name=\"f50def61-5681-4624-b41d-ee834bfbb350\" data-top-sold-product=\"false\">Se&#231;ilmiş Men&#252; (Tavuk D&#246;ner D&#252;r&#252;m)</a> </div> <div class=\"product-desc\"> Tavuk D&#246;ner D&#252;r&#252;m + Coca-Cola (33 cl.)\r</div> </div> <div class=\"product-price discounted\"> <span class=\"listed-price\">20,00 TL</span> <span class=\"price\">15,00 TL</span> </div> </li>";

        $html_with_product_photo = "<li> <div class=\"product\"> <div class=\"product-info\"> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"df14eb8e-8bd2-4147-b5af-9f5fd287078a\" data-category-name=\"f50def61-5681-4624-b41d-ee834bfbb350\" data-top-sold-product=\"false\"> <i class=\"ys-icons ys-icons-foto\" data-imagepath=\"//cdn.yemeksepeti.com/restaurant/TR_ISTANBUL/nd-doner-esenyurt-cinar-mah/hatay-usulu-et-doner-durum-combo-menu_big.jpg\" data-productname=\"Hatay Usul&#252; Et D&#246;ner D&#252;r&#252;m Combo Men&#252;\"></i> </a> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"df14eb8e-8bd2-4147-b5af-9f5fd287078a\" data-category-name=\"f50def61-5681-4624-b41d-ee834bfbb350\" data-top-sold-product=\"false\">Hatay Usul&#252; Et D&#246;ner D&#252;r&#252;m Combo Men&#252;</a> </div> <div class=\"product-desc\"> Hatay Usul&#252; Et D&#246;ner D&#252;r&#252;m + Patates Kızartması + Ayran (20 cl.)\r</div> </div> <div class=\"product-price\"> <span class=\"price\">30,50 TL</span> </div> </li>";

        $html_with_product_ran_out = "<li> <div class=\"product-actions\"> </div> <div class=\"product\"> <div class=\"product-info\"> <strong href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"\" data-product-id=\"b24b228c-d8b5-420f-a90a-9adf660d4255\" data-category-name=\"a0140dc8-e879-47a1-a5e6-9dbe3278c565\" data-top-sold-product=\"false\">Se&#231;ilmiş Men&#252; (Tavuk D&#246;ner D&#252;r&#252;m)</strong> <span class=\"orderWarning\">- Şu anda mevcut değil.</span> </div> <div class=\"product-desc\"> Tavuk D&#246;ner D&#252;r&#252;m + Coca-Cola (33 cl.)\r</div> </div> <div class=\"product-price discounted\"> <span class=\"listed-price\">22,00 TL</span> <span class=\"price\">18,50 TL</span> </div> </li>";

        $product_name = $this->bot->findProductNameFromHtml($html_without_product_photo);

        $this->assertEquals("Seçilmiş Menü (Tavuk Döner Dürüm)", $product_name);

        $product_name = $this->bot->findProductNameFromHtml($html_with_product_photo);

        $this->assertEquals("Hatay Usulü Et Döner Dürüm Combo Menü", $product_name);

        $product_name = $this->bot->findProductNameFromHtml($html_with_product_ran_out);

        $this->assertEquals("Seçilmiş Menü (Tavuk Döner Dürüm)", $product_name);
    }

    public function test_can_find_product_price_from_html()
    {
        $prices_with_discount = "<li> <div class=\"product\"> <div class=\"product-info\"> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"8921d366-c52c-41a0-bd3d-a9c87d4d6389\" data-category-name=\"f50def61-5681-4624-b41d-ee834bfbb350\" data-top-sold-product=\"false\">Se&#231;ilmiş Men&#252; (Tavuk D&#246;ner D&#252;r&#252;m)</a> </div> <div class=\"product-desc\"> Tavuk D&#246;ner D&#252;r&#252;m + Coca-Cola (33 cl.)\r</div> </div> <div class=\"product-price discounted\"> <span class=\"listed-price\">20,00 TL</span> <span class=\"price\">15,00 TL</span> </div> </li>";

        $price_without_discount = "<li> <div class=\"product\"> <div class=\"product-info\"> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"df14eb8e-8bd2-4147-b5af-9f5fd287078a\" data-category-name=\"f50def61-5681-4624-b41d-ee834bfbb350\" data-top-sold-product=\"false\"> <i class=\"ys-icons ys-icons-foto\" data-imagepath=\"//cdn.yemeksepeti.com/restaurant/TR_ISTANBUL/nd-doner-esenyurt-cinar-mah/hatay-usulu-et-doner-durum-combo-menu_big.jpg\" data-productname=\"Hatay Usul&#252; Et D&#246;ner D&#252;r&#252;m Combo Men&#252;\"></i> </a> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"df14eb8e-8bd2-4147-b5af-9f5fd287078a\" data-category-name=\"f50def61-5681-4624-b41d-ee834bfbb350\" data-top-sold-product=\"false\">Hatay Usul&#252; Et D&#246;ner D&#252;r&#252;m Combo Men&#252;</a> </div> <div class=\"product-desc\"> Hatay Usul&#252; Et D&#246;ner D&#252;r&#252;m + Patates Kızartması + Ayran (20 cl.)\r</div> </div> <div class=\"product-price\"> <span class=\"price\">30,50 TL</span> </div> </li>";

        $with_discount = $this->bot->findProductPriceFromHtml($prices_with_discount);

        $this->assertArrayHasKey("normal_price", $with_discount);
        $this->assertArrayHasKey("price", $with_discount);

        $this->assertEquals("20,00 TL", $with_discount["normal_price"]);
        $this->assertEquals("15,00 TL", $with_discount["price"]);

        $without_discount = $this->bot->findProductPriceFromHtml($price_without_discount);

        $this->assertArrayNotHasKey("normal_price", $without_discount);
        $this->assertArrayHasKey("price", $without_discount);

        $this->assertEquals("30,50 TL", $without_discount["price"]);
    }

    public function test_can_find_product_description_from_html()
    {
        $html = "<li> <div class=\"product\"> <div class=\"product-info\"> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"8921d366-c52c-41a0-bd3d-a9c87d4d6389\" data-category-name=\"f50def61-5681-4624-b41d-ee834bfbb350\" data-top-sold-product=\"false\">Se&#231;ilmiş Men&#252; (Tavuk D&#246;ner D&#252;r&#252;m)</a> </div> <div class=\"product-desc\"> Tavuk D&#246;ner D&#252;r&#252;m + Coca-Cola (33 cl.)\r</div> </div> <div class=\"product-price discounted\"> <span class=\"listed-price\">20,00 TL</span> <span class=\"price\">15,00 TL</span> </div> </li>";

        $category_name = $this->bot->findProductDescriptionFromHtml($html);

        $this->assertEquals("Tavuk Döner Dürüm + Coca-Cola (33 cl.)", $category_name);
    }

    public function test_can_find_product_image_from_html()
    {
        $html_with_product_image = "<li> <div class=\"product\"> <div class=\"product-info\"> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"df14eb8e-8bd2-4147-b5af-9f5fd287078a\" data-category-name=\"f50def61-5681-4624-b41d-ee834bfbb350\" data-top-sold-product=\"false\"> <i class=\"ys-icons ys-icons-foto\" data-imagepath=\"//cdn.yemeksepeti.com/restaurant/TR_ISTANBUL/nd-doner-esenyurt-cinar-mah/hatay-usulu-et-doner-durum-combo-menu_big.jpg\" data-productname=\"Hatay Usul&#252; Et D&#246;ner D&#252;r&#252;m Combo Men&#252;\"></i> </a> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"df14eb8e-8bd2-4147-b5af-9f5fd287078a\" data-category-name=\"f50def61-5681-4624-b41d-ee834bfbb350\" data-top-sold-product=\"false\">Hatay Usul&#252; Et D&#246;ner D&#252;r&#252;m Combo Men&#252;</a> </div> <div class=\"product-desc\"> Hatay Usul&#252; Et D&#246;ner D&#252;r&#252;m + Patates Kızartması + Ayran (20 cl.)\r</div> </div> <div class=\"product-price\"> <span class=\"price\">30,50 TL</span> </div> </li>";

        $image_path = $this->bot->findProductImagePathFromHtml($html_with_product_image);

        $this->assertEquals("//cdn.yemeksepeti.com/restaurant/TR_ISTANBUL/nd-doner-esenyurt-cinar-mah/hatay-usulu-et-doner-durum-combo-menu_big.jpg", $image_path);
    }
}
