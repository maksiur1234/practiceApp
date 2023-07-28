import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:80/api', // Zmień to na adres URL Twojego API Laravel
});

export default api;
