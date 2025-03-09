export interface UrlInput {
    url: string;
    expiresInSeconds?: number | null;
  }
  
  export interface UrlOutput {
    originalUrl: string;
    shortUrl: string;
    code: string;
    createdAt: string;
    expiresAt: string | null;
    visitCount: number;
  }
  
  export interface ApiError {
    error?: string;
    errors?: Record<string, string>;
  }