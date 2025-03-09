import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import UrlResult from './UrlResult';

// Mock de navigator.clipboard
Object.assign(navigator, {
  clipboard: {
    writeText: vi.fn(),
  },
});

// Mock de react-hot-toast
vi.mock('react-hot-toast', () => ({
  default: {
    success: vi.fn(),
    error: vi.fn(),
  },
}));

describe('UrlResult', () => {
  const mockUrlData = {
    originalUrl: 'https://example.com/long/url/path',
    shortUrl: 'http://localhost:8000/abc123',
    code: 'abc123',
    createdAt: '2023-01-01T12:00:00Z',
    expiresAt: '2024-01-01T12:00:00Z',
    visitCount: 5,
  };

  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('should render URL result correctly', () => {
    render(<UrlResult urlData={mockUrlData} />);
    
    expect(screen.getByText('Résultat')).toBeInTheDocument();
    expect(screen.getByDisplayValue('http://localhost:8000/abc123')).toBeInTheDocument();
    expect(screen.getByText('https://example.com/long/url/path')).toBeInTheDocument();
    expect(screen.getByText(/Visites: 5/i)).toBeInTheDocument();
    expect(screen.getByText(/Créée le:/i)).toBeInTheDocument();
    expect(screen.getByText(/Expire le:/i)).toBeInTheDocument();
  });

  it('should copy URL to clipboard when button is clicked', async () => {
    render(<UrlResult urlData={mockUrlData} />);
    
    const copyButton = screen.getByTitle('Copier l\'URL');
    fireEvent.click(copyButton);
    
    expect(navigator.clipboard.writeText).toHaveBeenCalledWith('http://localhost:8000/abc123');
  });

  it('should not show expiration date if expiresAt is null', () => {
    const dataWithoutExpiration = {
      ...mockUrlData,
      expiresAt: null,
    };
    
    render(<UrlResult urlData={dataWithoutExpiration} />);
    
    expect(screen.queryByText(/Expire le:/i)).not.toBeInTheDocument();
  });
});