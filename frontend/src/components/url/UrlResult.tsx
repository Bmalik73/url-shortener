import React, { useState } from 'react';
import { FaCopy, FaCheck, FaExternalLinkAlt, FaClock, FaEye } from 'react-icons/fa';
import toast from 'react-hot-toast';
import { UrlOutput } from '../../types/api';

interface UrlResultProps {
  urlData: UrlOutput;
}

const UrlResult: React.FC<UrlResultProps> = ({ urlData }) => {
  const [copied, setCopied] = useState(false);

  const copyToClipboard = async (text: string) => {
    try {
      await navigator.clipboard.writeText(text);
      setCopied(true);
      toast.success('Copié dans le presse-papier !');
      setTimeout(() => setCopied(false), 2000);
    } catch (err) {
      console.error('Failed to copy:', err);
      toast.error('Impossible de copier dans le presse-papier');
    }
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('fr-FR', {
      dateStyle: 'medium',
      timeStyle: 'short'
    }).format(date);
  };

  return (
    <div className="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
      <h3 className="mb-3 text-lg font-medium text-gray-900">Résultat</h3>
      
      <div className="mb-4">
        <div className="mb-1 text-sm font-medium text-gray-700">URL raccourcie</div>
        <div className="flex items-center">
          <input
            type="text"
            value={urlData.shortUrl}
            readOnly
            className="flex-1 px-3 py-2 bg-white border border-gray-300 rounded-l-md focus:outline-none"
          />
          <button
            onClick={() => copyToClipboard(urlData.shortUrl)}
            className="px-3 py-2 text-white bg-primary-600 rounded-r-md hover:bg-primary-700 focus:outline-none"
            title="Copier l'URL"
          >
            {copied ? <FaCheck className="w-5 h-5" /> : <FaCopy className="w-5 h-5" />}
          </button>
        </div>
      </div>
      
      <div className="mb-4">
        <div className="mb-1 text-sm font-medium text-gray-700">URL originale</div>
        <div className="flex">
          <div className="flex-1 px-3 py-2 overflow-hidden font-mono text-sm bg-white border border-gray-300 rounded-l-md truncate">
            {urlData.originalUrl}
          </div>
          <a
            href={urlData.originalUrl}
            target="_blank"
            rel="noopener noreferrer"
            className="px-3 py-2 text-white bg-gray-600 rounded-r-md hover:bg-gray-700 focus:outline-none"
            title="Ouvrir l'URL originale"
          >
            <FaExternalLinkAlt className="w-5 h-5" />
          </a>
        </div>
      </div>
      
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div className="flex items-center text-sm text-gray-600">
          <FaEye className="w-4 h-4 mr-2 text-primary-600" />
          <span>Visites: {urlData.visitCount}</span>
        </div>
        
        <div className="flex items-center text-sm text-gray-600">
          <FaClock className="w-4 h-4 mr-2 text-primary-600" />
          <span>Créée le: {formatDate(urlData.createdAt)}</span>
        </div>
        
        {urlData.expiresAt && (
          <div className="flex items-center text-sm text-gray-600">
            <FaClock className="w-4 h-4 mr-2 text-red-600" />
            <span>Expire le: {formatDate(urlData.expiresAt)}</span>
          </div>
        )}
      </div>
    </div>
  );
};

export default UrlResult;