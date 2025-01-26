Для развертывания должен быть установлен Docker Compose (v2.10+)

Клонируем репозиторий

  git clone https://github.com/rekkopavel/zdravsvyaz.git

Заходим  в папку с проектом и поднимаем контейнеры

  docker compose build --no-cache
  
  docker compose up --pull always -d --wait

Сайт должен быть доступен по адресу:

  https://localhost:4443


Можно залить тестовые данные из  docker volume. Он лежит в архиве base.tar в корне проекта.
Сначала смотрим где лежит текущий volume с данными базы 

  docker-compose down
  docker volume ls
  docker volume inspect <volume_name>

Копируем туда volume из архива

  sudo tar -xzvf base.tar -C /var/lib/docker/volumes/<volume_name>/


Доступ к базе если нужен

DATABASE_URL="postgresql://app:app@127.0.0.1:15432/app?serverVersion=16&charset=utf8"

POSTGRES_PASSWORD=app

POSTGRES_USER=app
