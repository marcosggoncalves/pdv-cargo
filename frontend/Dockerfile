FROM node:14

WORKDIR /usr/src/app

COPY package*.json ./

RUN npm install

RUN npm install typescript --save-dev 

COPY . .

CMD ["npm", "start"]
