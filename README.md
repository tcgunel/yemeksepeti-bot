[![License](https://poser.pugx.org/tcgunel/yemeksepeti-bot/license)](https://packagist.org/packages/tcgunel/yemeksepeti-bot)
[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen)](https://plant.treeware.earth/tcgunel/yemeksepeti-bot)
[![PHP Composer](https://github.com/tcgunel/yemeksepeti-bot/actions/workflows/tests.yml/badge.svg)](https://github.com/tcgunel/yemeksepeti-bot/actions/workflows/tests.yml)

[comment]: <> ([![PHP Composer]&#40;https://github.com/tcgunel/yemeksepeti-bot/actions/workflows/laravel8-tests.yml/badge.svg&#41;]&#40;https://github.com/tcgunel/yemeksepeti-bot/actions/workflows/laravel8-tests.yml&#41;)

# YemekSepeti Ürün Botu
Yemeksepeti sayfasındaki ürünlerin kategorize olarak çekilmesi.

## Requirements
| PHP            | Package |
|----------------|---------|
| ^7.3-^8.0-^9.0 | v1.0.0  |

## Kurulum

```
composer require tcgunel/yemeksepeti-bot
```

## Kullanım
İlgili url üzerinde botu çalıştırdıktan sonra getCategories metodu array içerisinde Category instancelarını dönecektir.
Her Category instanceda products attribute içerisinde Product[] instanceları bulunur.
```
$url = "https://www.yemeksepeti.com/burger-king-kadikoy-bostanci-mah-istanbul";

$bot = new YemeksepetiBot(null, $url);

$bot->run();

/** @var \TCGunel\YemeksepetiBot\Models\Category[] */
$bot->getCategories();
```

## Test
```
composer test
```
For windows:
```
vendor\bin\paratest.bat
```

## Authors

* [**Tolga Can GÜNEL**](https://github.com/tcgunel) - *Altyapı ve proje başlangıcı*

[comment]: <> (See also the list of [contributors]&#40;https://github.com/freshbitsweb/laravel-log-enhancer/graphs/contributors&#41; who participated in this project.)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## Treeware

This package is [Treeware](https://treeware.earth). If you use it in production, then we ask that you [**buy the world a tree**](https://plant.treeware.earth/tcgunel/yemeksepeti-bot) to thank us for our work. By contributing to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.
