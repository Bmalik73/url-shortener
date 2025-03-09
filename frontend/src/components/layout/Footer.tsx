import React from 'react';
import { FaHeart } from 'react-icons/fa';

const Footer: React.FC = () => {
  const currentYear = new Date().getFullYear();
  
  return (
    <footer className="py-6 mt-12 bg-white border-t border-gray-200">
      <div className="container">
        <div className="flex flex-col items-center justify-between gap-4 md:flex-row">
          <div className="text-sm text-gray-500">
            &copy; {currentYear} COTON URL Shortener
          </div>
          
          <div className="flex items-center text-sm text-gray-500">
            <span>Développé par Bokarka Abdelmalik avec</span>
            <FaHeart className="w-4 h-4 mx-1 text-red-500" />
            <span>pour le test technique COTON</span>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;