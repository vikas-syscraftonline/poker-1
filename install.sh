#!/bin/bash
sudo chgrp -R www-data tmp webroot/img/cache
chmod -R 775 tmp webroot/img/cache
find tmp -type f -exec chmod 664 {} \;
