# Используем Node.js версии 18, т.к. у вас в Docker Compose указана версия 18
FROM node

# Указываем рабочую директорию
WORKDIR /var/www/

# Копируем package.json и package-lock.json для установки зависимостей
COPY package*.json ./

# Устанавливаем зависимости
RUN npm install

# Копируем остальные файлы приложения в контейнер
COPY . .

# Открываем порт, на котором будет работать Vite (по умолчанию это 5175)
EXPOSE 5175

# Команда запуска приложения (будет использоваться в docker-compose)
CMD ["npm", "run", "dev"]
