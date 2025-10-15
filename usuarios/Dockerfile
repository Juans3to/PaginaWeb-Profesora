FROM node:18

# Crear directorio de trabajo
WORKDIR /app

# Copiar package.json y package-lock.json
COPY package*.json ./

# Instalar dependencias
RUN npm install

# Copiar el resto del código
COPY . .

# Exponer el puerto del servicio
EXPOSE 3001

# Comando para ejecutar el microservicio
CMD ["node", "src/index.js"]

