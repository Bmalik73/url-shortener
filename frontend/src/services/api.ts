import axios from 'axios';
import { UrlInput, UrlOutput } from '../types/api';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

export const urlService = {
  /**
   * Crée une URL raccourcie
   */
  shortenUrl: async (url: string, expiresInSeconds: number | null = null): Promise<UrlOutput> => {
    const data: UrlInput = { url };
    
    if (expiresInSeconds !== null) {
      data.expiresInSeconds = expiresInSeconds;
    }
    
    const response = await api.post<UrlOutput>('/urls', data);
    return response.data;
  },
  
  /**
   * Recherche l'URL originale à partir du code
   */
  lookupUrl: async (code: string): Promise<UrlOutput> => {
    const response = await api.post<UrlOutput>('/lookup', { code });
    return response.data;
  },
  
  /**
   * Récupère les informations d'une URL raccourcie
   */
  getUrlInfo: async (code: string): Promise<UrlOutput> => {
    const response = await api.get<UrlOutput>(`/urls/${code}`);
    return response.data;
  }
};

export default api;