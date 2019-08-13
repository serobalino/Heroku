import axios from 'axios';

const PREFIJO="/api";

export default {
    consulta(url) {
        return axios.post(`${PREFIJO}${url}`);
    },
    deploy(url){
        return axios.get(url);
    }
};
