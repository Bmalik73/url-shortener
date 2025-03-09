import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import ShortenForm from './ShortenForm';
import { urlService } from '../../services/api';

// Mock du service API
vi.mock('../../services/api', () => ({
  urlService: {
    shortenUrl: vi.fn(),
  },
}));

// Mock de react-hot-toast
vi.mock('react-hot-toast', () => ({
  default: {
    success: vi.fn(),
    error: vi.fn(),
  },
}));

describe('ShortenForm', () => {
  const mockSetUrlResult = vi.fn();

  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('should render the form correctly', () => {
    render(<ShortenForm setUrlResult={mockSetUrlResult} />);
    
    expect(screen.getByLabelText(/URL à raccourcir/i)).toBeInTheDocument();
    expect(screen.getByLabelText(/Durée de validité/i)).toBeInTheDocument();
    expect(screen.getByRole('button', { name: /Raccourcir l'URL/i })).toBeInTheDocument();
  });

  it('should show validation error for empty URL', async () => {
    render(<ShortenForm setUrlResult={mockSetUrlResult} />);
    
    const submitButton = screen.getByRole('button', { name: /Raccourcir l'URL/i });
    fireEvent.click(submitButton);
    
    expect(await screen.findByText(/L'URL est requise/i)).toBeInTheDocument();
  });

  it('should show validation error for invalid URL', async () => {
    render(<ShortenForm setUrlResult={mockSetUrlResult} />);
    
    const urlInput = screen.getByLabelText(/URL à raccourcir/i);
    fireEvent.change(urlInput, { target: { value: 'invalid-url' } });
    
    const submitButton = screen.getByRole('button', { name: /Raccourcir l'URL/i });
    fireEvent.click(submitButton);
    
    expect(await screen.findByText(/Veuillez entrer une URL valide/i)).toBeInTheDocument();
  });

  it('should call shortenUrl service with correct parameters', async () => {
    const mockResult = {
      originalUrl: 'https://example.com',
      shortUrl: 'http://localhost:8000/abc123',
      code: 'abc123',
      createdAt: '2023-01-01T00:00:00Z',
      expiresAt: null,
      visitCount: 0,
    };
    
    // @ts-ignore
    urlService.shortenUrl.mockResolvedValueOnce(mockResult);
    
    render(<ShortenForm setUrlResult={mockSetUrlResult} />);
    
    const urlInput = screen.getByLabelText(/URL à raccourcir/i);
    fireEvent.change(urlInput, { target: { value: 'https://example.com' } });
    
    const submitButton = screen.getByRole('button', { name: /Raccourcir l'URL/i });
    fireEvent.click(submitButton);
    
    expect(urlService.shortenUrl).toHaveBeenCalledWith('https://example.com', null);
    
    // Attendre que la promesse soit résolue
    await vi.waitFor(() => {
      expect(mockSetUrlResult).toHaveBeenCalledWith(mockResult);
    });
  });
});