# This data in this file is not secret, since it is only used to setup a new environment with an
# empty database in e.g. docker. Move this into .env
APP_NAME=Wishlist
APP_ENV=local
APP_KEY=base64:udq6u6TckmPCBQ+5KnFIY0mlhhDNT9DV4SBOcsbGP3Q=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=wishlist
DB_ROOT_PASSWORD=
DB_USERNAME=Laravel
DB_PASSWORD=notimportant1234

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=memcached

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://meilisearch:7700

SNEAKER_IMAGES_LOCATION=https://www.sneakerdistrict.com/
SNEAKER_PRODUCTS_ENDPOINT=https://api.sneakerdistrict.com/products
DOING_GOOD_PRODUCTS_ENDPOINT=https://www.doing-goods.com/wp-json/lamapress/v1/products

WWWGROUP=1234
WWWUSER=sail
