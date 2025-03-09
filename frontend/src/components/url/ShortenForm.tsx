import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { FaSpinner, FaLink } from 'react-icons/fa';
import { urlService } from '../../services/api';
import toast from 'react-hot-toast';
import { UrlOutput } from '../../types/api';

interface ShortenFormProps {
  setUrlResult: (result: UrlOutput | null) => void;
}

interface FormData {
  url: string;
  expiresIn: string;
}

const ShortenForm: React.FC<ShortenFormProps> = ({ setUrlResult }) => {
  const [isLoading, setIsLoading] = useState(false);
  const { register, handleSubmit, formState: { errors }, reset } = useForm<FormData>();

  const onSubmit = async (data: FormData) => {
    setIsLoading(true);
    try {
      const expiresInSeconds = data.expiresIn ? parseInt(data.expiresIn) : null;
      const result = await urlService.shortenUrl(data.url, expiresInSeconds);
      setUrlResult(result);
      reset();
      toast.success('URL raccourcie avec succès !');
    } catch (error: any) {
      console.error('Error shortening URL:', error);
      toast.error(error.response?.data?.errors?.url || 'Erreur lors du raccourcissement de l\'URL');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div>
        <label htmlFor="url" className="block mb-1 text-sm font-medium text-gray-700">
          URL à raccourcir
        </label>
        <input
          id="url"
          type="text"
          placeholder="https://exemple.com/ma-longue-url"
          className={`input ${errors.url ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}`}
          {...register('url', {
            required: 'L\'URL est requise',
            pattern: {
              value: /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/,
              message: 'Veuillez entrer une URL valide'
            }
          })}
        />
        {errors.url && (
          <p className="mt-1 text-sm text-red-600">{errors.url.message}</p>
        )}
      </div>

      <div>
        <label htmlFor="expiresIn" className="block mb-1 text-sm font-medium text-gray-700">
          Durée de validité (optionnel)
        </label>
        <select
          id="expiresIn"
          className="input"
          {...register('expiresIn')}
        >
          <option value="">Pas d'expiration</option>
          <option value="3600">1 heure</option>
          <option value="86400">1 jour</option>
          <option value="604800">1 semaine</option>
          <option value="2592000">1 mois</option>
          <option value="31536000">1 an</option>
        </select>
      </div>

      <div className="pt-2">
        <button
          type="submit"
          className="w-full btn btn-primary"
          disabled={isLoading}
        >
          {isLoading ? (
            <>
              <FaSpinner className="w-4 h-4 mr-2 animate-spin" />
              Traitement en cours...
            </>
          ) : (
            <>
              <FaLink className="w-4 h-4 mr-2" />
              Raccourcir l'URL
            </>
          )}
        </button>
      </div>
    </form>
  );
};

export default ShortenForm;