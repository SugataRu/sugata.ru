Репозиторий создан...

# Установка

Обратите внимание на пути в файле: config.php

```
$globals['haanga_cache'] = '/Ampps/www/r/tmp';
$globals['jms_cache'] = '/Ampps/www/r/tmp';
```

И на строчку в файле: vendor/crodas/haanga/lib/Haanga/Compiler/Tokenizer.php

```
file_put_contents('/Ampps/www/r/tmp/foo.php', $file . "\n", FILE_APPEND);
```

## Читать далее

https://github.com/SugataRu/sugata.ru/wiki


### Сайт проекте

https://sugata.ru

---

Клонирование Menéame: https://github.com/Meneame/meneame.net
