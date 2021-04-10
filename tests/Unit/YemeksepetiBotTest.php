<?php

namespace TCGunel\YemeksepetiBot\Tests\Unit;

use TCGunel\YemeksepetiBot\Constants\ViewMode;
use TCGunel\YemeksepetiBot\Tests\TestCase;

class YemeksepetiBotTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_find_category_name_from_html()
    {
        $html = "restaurantDetailBox CocaCola double' id=\"menu_0\"> <div class=\"head white\"> <h2><b>Coca-Cola Se&#231;ilmiş Men&#252;leri</b></h2> <div class=\"description\"></div> </div> <div class=\"listBody\"> <ul> <li> <div class=\"product\"> <div class=\"product-info\"> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"8921d366-c52c-41a0-bd3d-a9c87d4d6389\" data-category-name=\"f50def61-5681-4624-b41d-ee834bfbb350\" data-top-sold-product=\"false\">Se&#231;ilmiş Men&#252; (Tavuk D&#246;ner D&#252;r&#252;m)</a> </div> <div class=\"product-desc\"> Tavuk D&#246;ner D&#252;r&#252;m + Coca-Cola (33 cl.)\r</div> </div> <div class=\"product-price discounted\"> <span class=\"listed-price\">20,00 TL</span> <span class=\"price\">15,00 TL</span> </div> </li> <li> <div class=\"product\"> <div class=\"product-info\"> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"22a18f6e-f23e-4bd9-a5bc-c8b0c8a1fdaf\" data-category-name=\"f50def61-5681-4624-b41d-ee834bfbb350\" data-top-sold-product=\"false\">Se&#231;ilmiş Men&#252; (Nd Steak Burger)</a> </div> <div class=\"product-desc\"> ND Steak Burger + Coca-Cola (33 cl.)\r</div> </div> <div class=\"product-price discounted\"> <span class=\"listed-price\">44,00 TL</span> <span class=\"price\">37,25 TL</span> </div> </li> </ul>";

        $category_name = $this->bot->findCategoryNameFromHtml($html);

        $this->assertEquals("Coca-Cola Seçilmiş Menüleri", $category_name);
    }

    public function test_can_find_product_id_from_html()
    {
        $html = "<li> <div class=\"product\"> <div class=\"product-info\"> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"8921d366-c52c-41a0-bd3d-a9c87d4d6389\" data-category-name=\"f50def61-5681-4624-b41d-ee834bfbb350\" data-top-sold-product=\"false\">Se&#231;ilmiş Men&#252; (Tavuk D&#246;ner D&#252;r&#252;m)</a> </div> <div class=\"product-desc\"> Tavuk D&#246;ner D&#252;r&#252;m + Coca-Cola (33 cl.)\r</div> </div> <div class=\"product-price discounted\"> <span class=\"listed-price\">20,00 TL</span> <span class=\"price\">15,00 TL</span> </div> </li>";

        $product_id = $this->bot->findProductIdFromHtml($html);

        $this->assertEquals("8921d366-c52c-41a0-bd3d-a9c87d4d6389", $product_id);
    }

    public function test_can_determine_view_mode_from_html()
    {
        $list_view_html = file_get_contents(__DIR__ . "/../ListViewExampleHtmlContent.html");

        $grid_view_html = file_get_contents(__DIR__ . "/../GridViewExampleHtmlContent.html");

        $this->bot->setViewMode($list_view_html);

        $this->assertEquals(ViewMode::LIST_VIEW, $this->bot->getViewMode());

        $this->bot->setViewMode($grid_view_html);

        $this->assertEquals(ViewMode::GRID_VIEW, $this->bot->getViewMode());
    }
}
