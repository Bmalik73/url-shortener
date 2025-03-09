import React from 'react';
import { Link } from 'react-router-dom';
import { FaExclamationTriangle, FaArrowLeft } from 'react-icons/fa';

const NotFoundPage: React.FC = () => {
  return (
    <div className="container py-16">
      <div className="max-w-2xl p-8 mx-auto text-center card">
        <FaExclamationTriangle className="w-12 h-12 mx-auto mb-4 text-red-500" />
        <h1 className="mb-4 text-3xl font-bold text-gray-900">Page non trouvée</h1>
        <p className="mb-8 text-gray-600">
          La page que vous recherchez n'existe pas ou a été déplacée.
        </p>
        <Link to="/" className="btn btn-primary">
          <FaArrowLeft className="mr-2" /> Retour à l'accueil
        </Link>
      </div>
    </div>
  );
};

export default NotFoundPage;