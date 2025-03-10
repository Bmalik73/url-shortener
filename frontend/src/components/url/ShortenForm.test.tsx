import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen, fireEvent, act } from '@testing-library/react';
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
    // Arrange
    const mockShortenUrl = vi.fn().mockResolvedValue({ shortUrl: 'http://short.url/abc123' });
    vi.spyOn(urlService, 'shortenUrl').mockImplementation(mockShortenUrl);
    
    render(<ShortenForm setUrlResult={vi.fn()} />);
    
    // Act
    const urlInput = screen.getByLabelText(/URL à raccourcir/i);
    const submitButton = screen.getByRole('button', { name: /Raccourcir/i });
    
    await act(async () => {
      fireEvent.change(urlInput, { target: { value: 'https://example.com' } });
      fireEvent.click(submitButton);
    });
    
    // Assert
    expect(urlService.shortenUrl).toHaveBeenCalledWith('https://example.com', null);
  });
});