import React, { useState } from 'react';
import ShortenForm from '../components/url/ShortenForm';
import LookupForm from '../components/url/LookupForm';
import UrlResult from '../components/url/UrlResult';
import { FaLink, FaSearch } from 'react-icons/fa';
import { UrlOutput } from '../types/api';

const HomePage: React.FC = () => {
  const [activeTab, setActiveTab] = useState<'shorten' | 'lookup'>('shorten');
  const [urlResult, setUrlResult] = useState<UrlOutput | null>(null);

  return (
    <div className="container py-12">
      <div className="max-w-3xl mx-auto">
        <div className="text-center mb-10">
          <h1 className="mb-3 text-3xl font-bold text-gray-900 sm:text-4xl">
            Raccourcissez vos URLs en un instant
          </h1>
          <p className="max-w-2xl mx-auto text-lg text-gray-600">
            Un service simple et rapide pour transformer vos liens longs en URLs courtes et faciles à partager.
          </p>
        </div>

        <div className="card">
          <div className="flex mb-6 overflow-hidden border rounded-lg border-gray-200">
            <button
              className={`flex-1 px-4 py-3 text-sm font-medium flex items-center justify-center gap-2 ${
                activeTab === 'shorten'
                  ? 'bg-primary-600 text-white'
                  : 'bg-white text-gray-600 hover:bg-gray-50'
              }`}
              onClick={() => setActiveTab('shorten')}
            >
              <FaLink className="w-4 h-4" />
              <span>Raccourcir une URL</span>
            </button>
            <button
              className={`flex-1 px-4 py-3 text-sm font-medium flex items-center justify-center gap-2 ${
                activeTab === 'lookup'
                  ? 'bg-primary-600 text-white'
                  : 'bg-white text-gray-600 hover:bg-gray-50'
              }`}
              onClick={() => setActiveTab('lookup')}
            >
              <FaSearch className="w-4 h-4" />
              <span>Chercher une URL</span>
            </button>
          </div>

          {activeTab === 'shorten' ? (
            <ShortenForm setUrlResult={setUrlResult} />
          ) : (
            <LookupForm setUrlResult={setUrlResult} />
          )}

          {urlResult && <UrlResult urlData={urlResult} />}
        </div>
      </div>
    </div>
  );
};

export default HomePage;