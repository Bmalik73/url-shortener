import React, { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import { urlService } from '../services/api';
import { FaSpinner, FaExclamationTriangle, FaArrowLeft } from 'react-icons/fa';

type PageStatus = 'loading' | 'error' | 'redirecting';

const RedirectPage: React.FC = () => {
  const { code } = useParams<{ code: string }>();
  const [status, setStatus] = useState<PageStatus>('loading');
  const [originalUrl, setOriginalUrl] = useState<string>('');
  const [error, setError] = useState<string>('');

  useEffect(() => {
    const fetchOriginalUrl = async () => {
      if (!code) return;
      
      try {
        const result = await urlService.getUrlInfo(code);
        setOriginalUrl(result.originalUrl);
        setStatus('redirecting');
        
        // Redirect after a short delay
        setTimeout(() => {
          window.location.href = result.originalUrl;
        }, 1500);
      } catch (err: any) {
        setStatus('error');
        setError(err.response?.data?.error || 'URL introuvable ou expirée');
      }
    };

    if (code) {
      fetchOriginalUrl();
    }
  }, [code]);

  if (status === 'loading') {
    return (
      <div className="container py-16">
        <div className="max-w-2xl p-8 mx-auto text-center card">
          <FaSpinner className="w-12 h-12 mx-auto mb-4 text-primary-600 animate-spin" />
          <h1 className="mb-4 text-2xl font-bold">Chargement en cours...</h1>
          <p className="text-gray-600">
            Nous récupérons l'URL originale associée au code <strong>{code}</strong>
          </p>
        </div>
      </div>
    );
  }

  if (status === 'error') {
    return (
      <div className="container py-16">
        <div className="max-w-2xl p-8 mx-auto text-center card">
          <FaExclamationTriangle className="w-12 h-12 mx-auto mb-4 text-red-500" />
          <h1 className="mb-4 text-2xl font-bold text-red-600">Erreur</h1>
          <p className="mb-6 text-gray-600">{error}</p>
          <Link to="/" className="btn btn-primary">
            <FaArrowLeft className="mr-2" /> Retour à l'accueil
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="container py-16">
      <div className="max-w-2xl p-8 mx-auto text-center card">
        <FaSpinner className="w-12 h-12 mx-auto mb-4 text-primary-600 animate-spin" />
        <h1 className="mb-4 text-2xl font-bold">Redirection en cours...</h1>
        <p className="mb-2 text-gray-600">
          Vous allez être redirigé vers :
        </p>
        <p className="p-2 mb-6 overflow-hidden text-sm font-mono text-gray-800 bg-gray-100 rounded-md truncate">
          {originalUrl}
        </p>
        <p className="text-sm text-gray-500">
          Si la redirection ne fonctionne pas, <a href={originalUrl} className="text-primary-600 hover:underline">cliquez ici</a>.
        </p>
      </div>
    </div>
  );
};

export default RedirectPage;