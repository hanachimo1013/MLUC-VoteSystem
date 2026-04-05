import axios from 'axios'
import store from './store'

const createAxiosInstance = (baseURL, tokenStatePath = null) => {
  const instance = axios.create({
    baseURL,
    withCredentials: true
  })

  instance.interceptors.request.use(request => {
    request.headers.common['Access-Control-Allow-Origin'] = 'http://localhost:8000';
    request.headers.common['Accept'] = 'application/json';
    request.headers.common['Content-Type'] = 'application/json';

    if (tokenStatePath) {
      const token = tokenStatePath === 'a' ? store.state.a.user.token : store.state.b.user.token;
      if (token) {
        request.headers.Authorization = `Bearer ${token}`
      }
    }
    return request;
  });

  instance.interceptors.response.use(
    response => response,
    error => Promise.reject(error)
  );

  return instance;
}

export const axiosClient = createAxiosInstance('http://localhost:8000/api/', 'a');
export const axiosClientVoter = createAxiosInstance('http://localhost:8000/api', 'b');
export const axiosSanctum = createAxiosInstance('http://localhost:8000/', 'a');

export const axiosOrigin = axios.create({
  baseURL: 'http://localhost:8001/api/'
});

axiosOrigin.interceptors.request.use(request => {
  request.headers.common['Accept'] = 'application/json';
  request.headers.common['Content-Type'] = 'application/json';
  return request;
});

axiosOrigin.interceptors.response.use(
  response => response,
  error => Promise.reject(error)
);

export default axiosClient;
