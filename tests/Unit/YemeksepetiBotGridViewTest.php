<?php

namespace TCGunel\YemeksepetiBot\Tests\Unit;

use TCGunel\YemeksepetiBot\Models\Category;
use TCGunel\YemeksepetiBot\Models\Product;
use TCGunel\YemeksepetiBot\Tests\TestCase;

class YemeksepetiBotGridViewTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->html_content = file_get_contents(__DIR__ . "/../GridViewExampleHtmlContent.html");

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
        $html_with_product_photo = "<li> <div class=\"table-row\"> <div class=\"product-image is-available\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\"> <img src=\"/assets/images/no-product-image.png\" data-src=\"//cdn.yemeksepeti.com/ProductImages/TR_ISTANBUL/burger_king_guncel/1885_BK_Kampanyalar_Bigroyale400x400_big.jpg\" /> </div> <div class=\"product-detail-info is-available\"> <div class=\"productName\"> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\"> </a> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\">Se&#231;ilmiş Men&#252; (Big Royale Kampanyası)</a> </div> <span class=\"productInfo\"> <p>Big King&#174; + Chicken Royale&#174; + B&#252;y&#252;k Boy Patates + 4&#39;l&#252; Soğan Halkası + 1 L. İ&#231;ecek</p> </span> <div class=\"product-actions\"> <input type=\"text\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" class=\"item-count\" value=\"1\" /> <button aria-label=\"Sepete Ekle\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-catalog-name=\"TR_ISTANBUL\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\" class=\"ys-btn ys-btn-primary ys-btn-icon add-to-basket\"><i class=\"ys-icons ys-icons-plus\"></i></button> <div class=\"price-wrapper\"> <del class=\"listedPrice\">54,00 TL</del> <span class=\"newPrice\">44,99 TL</span> </div> </div> </div> </div> </li>";

        $product_name = $this->bot->findProductNameFromHtml($html_with_product_photo);

        $this->assertEquals("Seçilmiş Menü (Big Royale Kampanyası)", $product_name);
    }

    public function test_can_find_product_price_from_html()
    {
        $prices_with_discount = "<li> <div class=\"table-row\"> <div class=\"product-image is-available\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\"> <img src=\"/assets/images/no-product-image.png\" data-src=\"//cdn.yemeksepeti.com/ProductImages/TR_ISTANBUL/burger_king_guncel/1885_BK_Kampanyalar_Bigroyale400x400_big.jpg\" /> </div> <div class=\"product-detail-info is-available\"> <div class=\"productName\"> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\"> </a> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\">Se&#231;ilmiş Men&#252; (Big Royale Kampanyası)</a> </div> <span class=\"productInfo\"> <p>Big King&#174; + Chicken Royale&#174; + B&#252;y&#252;k Boy Patates + 4&#39;l&#252; Soğan Halkası + 1 L. İ&#231;ecek</p> </span> <div class=\"product-actions\"> <input type=\"text\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" class=\"item-count\" value=\"1\" /> <button aria-label=\"Sepete Ekle\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-catalog-name=\"TR_ISTANBUL\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\" class=\"ys-btn ys-btn-primary ys-btn-icon add-to-basket\"><i class=\"ys-icons ys-icons-plus\"></i></button> <div class=\"price-wrapper\"> <del class=\"listedPrice\">54,00 TL</del> <span class=\"newPrice\">44,99 TL</span> </div> </div> </div> </div> </li>";

        $price_without_discount = "<li> <div class=\"table-row\"> <div class=\"product-image is-available\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\"> <img src=\"/assets/images/no-product-image.png\" data-src=\"//cdn.yemeksepeti.com/ProductImages/TR_ISTANBUL/burger_king_guncel/1885_BK_Kampanyalar_Bigroyale400x400_big.jpg\" /> </div> <div class=\"product-detail-info is-available\"> <div class=\"productName\"> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\"> </a> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\">Se&#231;ilmiş Men&#252; (Big Royale Kampanyası)</a> </div> <span class=\"productInfo\"> <p>Big King&#174; + Chicken Royale&#174; + B&#252;y&#252;k Boy Patates + 4&#39;l&#252; Soğan Halkası + 1 L. İ&#231;ecek</p> </span> <div class=\"product-actions\"> <input type=\"text\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" class=\"item-count\" value=\"1\" /> <button aria-label=\"Sepete Ekle\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-catalog-name=\"TR_ISTANBUL\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\" class=\"ys-btn ys-btn-primary ys-btn-icon add-to-basket\"><i class=\"ys-icons ys-icons-plus\"></i></button> <div class=\"price-wrapper\"> <span class=\"newPrice\">44,99 TL</span> </div> </div> </div> </div> </li>";

        $with_discount = $this->bot->findProductPriceFromHtml($prices_with_discount);

        $this->assertArrayHasKey("normal_price", $with_discount);
        $this->assertArrayHasKey("price", $with_discount);

        $this->assertEquals("54,00 TL", $with_discount["normal_price"]);
        $this->assertEquals("44,99 TL", $with_discount["price"]);

        $without_discount = $this->bot->findProductPriceFromHtml($price_without_discount);

        $this->assertArrayNotHasKey("normal_price", $without_discount);
        $this->assertArrayHasKey("price", $without_discount);

        $this->assertEquals("44,99 TL", $without_discount["price"]);
    }

    public function test_can_find_product_description_from_html()
    {
        $html = "<li> <div class=\"table-row\"> <div class=\"product-image is-available\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\"> <img src=\"/assets/images/no-product-image.png\" data-src=\"//cdn.yemeksepeti.com/ProductImages/TR_ISTANBUL/burger_king_guncel/1885_BK_Kampanyalar_Bigroyale400x400_big.jpg\" /> </div> <div class=\"product-detail-info is-available\"> <div class=\"productName\"> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\"> </a> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\">Se&#231;ilmiş Men&#252; (Big Royale Kampanyası)</a> </div> <span class=\"productInfo\"> <p>Big King&#174; + Chicken Royale&#174; + B&#252;y&#252;k Boy Patates + 4&#39;l&#252; Soğan Halkası + 1 L. İ&#231;ecek</p> </span> <div class=\"product-actions\"> <input type=\"text\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" class=\"item-count\" value=\"1\" /> <button aria-label=\"Sepete Ekle\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-catalog-name=\"TR_ISTANBUL\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\" class=\"ys-btn ys-btn-primary ys-btn-icon add-to-basket\"><i class=\"ys-icons ys-icons-plus\"></i></button> <div class=\"price-wrapper\"> <del class=\"listedPrice\">54,00 TL</del> <span class=\"newPrice\">44,99 TL</span> </div> </div> </div> </div> </li>";

        $category_name = $this->bot->findProductDescriptionFromHtml($html);

        $this->assertEquals("Big King® + Chicken Royale® + Büyük Boy Patates + 4'lü Soğan Halkası + 1 L. İçecek", $category_name);
    }

    public function test_can_find_product_image_from_html()
    {
        $html_with_product_image = "<li> <div class=\"table-row\"> <div class=\"product-image is-available\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\"> <img src=\"/assets/images/no-product-image.png\" data-src=\"//cdn.yemeksepeti.com/ProductImages/TR_ISTANBUL/burger_king_guncel/1885_BK_Kampanyalar_Bigroyale400x400_big.jpg\" /> </div> <div class=\"product-detail-info is-available\"> <div class=\"productName\"> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\"> </a> <a href=\"javascript:void(0);\" data-catalog-name=\"TR_ISTANBUL\" class=\"getProductDetail\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\">Se&#231;ilmiş Men&#252; (Big Royale Kampanyası)</a> </div> <span class=\"productInfo\"> <p>Big King&#174; + Chicken Royale&#174; + B&#252;y&#252;k Boy Patates + 4&#39;l&#252; Soğan Halkası + 1 L. İ&#231;ecek</p> </span> <div class=\"product-actions\"> <input type=\"text\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" class=\"item-count\" value=\"1\" /> <button aria-label=\"Sepete Ekle\" data-product-id=\"e24ce3e4-4e75-4b30-9577-95d1688680d9\" data-catalog-name=\"TR_ISTANBUL\" data-category-name=\"6dc17203-687f-4143-969e-fb6ca956f34d\" data-top-sold-product=\"false\" class=\"ys-btn ys-btn-primary ys-btn-icon add-to-basket\"><i class=\"ys-icons ys-icons-plus\"></i></button> <div class=\"price-wrapper\"> <del class=\"listedPrice\">54,00 TL</del> <span class=\"newPrice\">44,99 TL</span> </div> </div> </div> </div> </li>";

        $image_path = $this->bot->findProductImagePathFromHtml($html_with_product_image);

        $this->assertEquals("//cdn.yemeksepeti.com/ProductImages/TR_ISTANBUL/burger_king_guncel/1885_BK_Kampanyalar_Bigroyale400x400_big.jpg", $image_path);
    }
}
