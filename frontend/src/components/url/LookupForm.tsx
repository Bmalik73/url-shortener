import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { FaSpinner, FaSearch } from 'react-icons/fa';
import { urlService } from '../../services/api';
import toast from 'react-hot-toast';
import { UrlOutput } from '../../types/api';

interface LookupFormProps {
  setUrlResult: (result: UrlOutput | null) => void;
}

interface FormData {
  code: string;
}

const LookupForm: React.FC<LookupFormProps> = ({ setUrlResult }) => {
  const [isLoading, setIsLoading] = useState(false);
  const { register, handleSubmit, formState: { errors }, reset } = useForm<FormData>();

  const onSubmit = async (data: FormData) => {
    setIsLoading(true);
    try {
      let code = data.code.trim();
      // Si l'utilisateur entre une URL complète, extrayez juste le code
      if (code.includes('/')) {
        code = code.split('/').pop() || '';
      }
      
      const result = await urlService.lookupUrl(code);
      setUrlResult(result);
      reset();
    } catch (error: any) {
      console.error('Error looking up URL:', error);
      toast.error(error.response?.data?.error || 'URL introuvable ou expirée');
      setUrlResult(null);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div>
        <label htmlFor="code" className="block mb-1 text-sm font-medium text-gray-700">
          Code ou URL raccourcie
        </label>
        <input
          id="code"
          type="text"
          placeholder="abc123 ou https://coton.dev/abc123"
          className={`input ${errors.code ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}`}
          {...register('code', {
            required: 'Le code est requis',
          })}
        />
        {errors.code && (
          <p className="mt-1 text-sm text-red-600">{errors.code.message}</p>
        )}
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
              Recherche en cours...
            </>
          ) : (
            <>
              <FaSearch className="w-4 h-4 mr-2" />
              Rechercher l'URL
            </>
          )}
        </button>
      </div>
    </form>
  );
};

export default LookupForm;