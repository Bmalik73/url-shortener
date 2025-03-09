import React from 'react';
import { Link } from 'react-router-dom';
import { FaLink } from 'react-icons/fa';

const Header: React.FC = () => {
  return (
    <header className="bg-white shadow">
      <div className="container py-4 mx-auto">
        <div className="flex items-center justify-between">
          <Link to="/" className="flex items-center gap-2 text-xl font-bold text-primary-600">
            <FaLink className="w-6 h-6" />
            <span>COTON URL</span>
          </Link>
          
          <nav className="flex items-center space-x-4">
            <Link to="/" className="text-sm font-medium text-gray-600 hover:text-primary-600">
              Raccourcir
            </Link>
            <a 
              href="https://github.com/votre-username/url-shortener" 
              target="_blank" 
              rel="noopener noreferrer"
              className="text-sm font-medium text-gray-600 hover:text-primary-600"
            >
              GitHub
            </a>
          </nav>
        </div>
      </div>
    </header>
  );
};

export default Header;