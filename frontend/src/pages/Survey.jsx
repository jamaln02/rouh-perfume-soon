import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { ArrowRight, ArrowLeft, Gift, CheckCircle2, Copy, Check, AlertCircle } from 'lucide-react';
import { Button } from '../components/ui/button';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { Textarea } from '../components/ui/textarea';
import { RadioGroup, RadioGroupItem } from '../components/ui/radio-group';
import axios from 'axios';

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL || 'https://api.rouh.shop/api';

const Survey = () => {
  const navigate = useNavigate();
  const [currentStep, setCurrentStep] = useState(0);
  const [copied, setCopied] = useState(false);
  const [loading, setLoading] = useState(false);
  const [discountCode, setDiscountCode] = useState('');
  const [error, setError] = useState('');
  const [isDuplicate, setIsDuplicate] = useState(false);

  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    age: '',
    gender: '',
    location: '',
    perfumeQuality: '',
    favoritePerfumes: '',
    purchaseFrequency: '',
    mainProblem: '',
    priceRange: '',
    wishList: ''
  });

  // الاستبيان المحسن - 8 خطوات فقط
  const questions = [
    {
      id: 'personal',
      title: 'معلوماتك الشخصية',
      subtitle: 'بدنا نتواصل معك لو فزت بالخصم',
      fields: [
        { name: 'name', label: 'الاسم الكامل', type: 'text', required: true, placeholder: 'أدخل اسمك' },
        { name: 'phone', label: 'رقم الموبايل', type: 'tel', required: true, placeholder: '+963 XXX XXX XXX' },
        { name: 'email', label: 'البريد الإلكتروني (اختياري)', type: 'email', required: false, placeholder: 'example@email.com' },
        { name: 'age', label: 'عمرك', type: 'number', required: true, placeholder: '25' },
        { name: 'location', label: 'مدينتك / منطقتك', type: 'text', required: true, placeholder: 'دمشق، حلب، اللاذقية...' }
      ]
    },
    {
      id: 'gender',
      title: 'الجنس',
      subtitle: 'مشان نفهم تفضيلاتك أحسن',
      type: 'radio',
      name: 'gender',
      options: [
        { value: 'male', label: 'ذكر' },
        { value: 'female', label: 'أنثى' }
      ]
    },
    {
      id: 'quality',
      title: 'نوع العطور اللي بتشتريها',
      subtitle: 'بتفضل أورجينال ولا تركيبات؟',
      type: 'radio',
      name: 'perfumeQuality',
      options: [
        { value: 'original', label: 'أورجينال (Original)' },
        { value: 'tarkib', label: 'تركيبات' },
        { value: 'both', label: 'الاثنين' }
      ]
    },
    {
      id: 'favorites',
      title: 'عطورك المفضلة',
      subtitle: 'شو أكتر عطر بتحبه حالياً؟',
      fields: [
        { name: 'favoritePerfumes', label: '', type: 'textarea', required: true, placeholder: 'مثلاً: Dior Sauvage, Chanel، أو أي عطر...' }
      ]
    },
    {
      id: 'frequency',
      title: 'كم مرة بتشتري عطر؟',
      subtitle: 'عاداتك الشرائية',
      type: 'radio',
      name: 'purchaseFrequency',
      options: [
        { value: 'monthly', label: 'شهرياً' },
        { value: 'quarterly', label: 'كل 3 أشهر' },
        { value: 'biannually', label: 'كل 6 أشهر' },
        { value: 'yearly', label: 'سنوياً' },
        { value: 'rarely', label: 'نادراً' }
      ]
    },
    {
      id: 'problem',
      title: 'أكبر مشكلة بتواجهك',
      subtitle: 'شو أكتر شي بيزعجك لما تشتري عطر؟',
      fields: [
        { name: 'mainProblem', label: '', type: 'textarea', required: true, placeholder: 'مثلاً: العطر ما بيدوم، السعر غالي...' }
      ]
    },
    {
      id: 'price',
      title: 'الميزانية',
      subtitle: formData.perfumeQuality === 'tarkib' 
        ? 'شو الميزانية اللي مستعد تدفعها لتركيبة عطر؟'
        : 'شو الميزانية اللي مستعد تدفعها لعطر أورجينال؟',
      type: 'radio',
      name: 'priceRange',
      options: formData.perfumeQuality === 'tarkib' ? [
        { value: 'under100k', label: 'أقل من 100 ألف ليرة' },
        { value: '100k-250k', label: '100 - 250 ألف ليرة' },
        { value: '250k-500k', label: '250 - 500 ألف ليرة' },
        { value: 'over500k', label: 'أكثر من 500 ألف ليرة' }
      ] : [
        { value: 'under50', label: 'أقل من 50 دولار' },
        { value: '50-100', label: '50 - 100 دولار' },
        { value: '100-200', label: '100 - 200 دولار' },
        { value: 'over200', label: 'أكثر من 200 دولار' }
      ]
    },
    {
      id: 'wishlist',
      title: 'حلمك بالعطر المثالي',
      subtitle: 'لو بتقدر تصمم عطرك المثالي، شو بيكون فيه؟',
      fields: [
        { name: 'wishList', label: '', type: 'textarea', required: true, placeholder: 'احكيلنا عن الرائحة، الثبات، التصميم...' }
      ]
    }
  ];

  const currentQuestion = questions[currentStep];
  const isLastStep = currentStep === questions.length - 1;

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
    setError('');
  };

  const handleRadioChange = (name, value) => {
    setFormData(prev => ({ ...prev, [name]: value }));
    setError('');
  };

  const validateStep = () => {
    if (currentQuestion.fields) {
      for (let field of currentQuestion.fields) {
        if (field.required && !formData[field.name]?.trim()) {
          setError(`الرجاء إدخال ${field.label}`);
          return false;
        }
      }
    }
    if (currentQuestion.type === 'radio' && !formData[currentQuestion.name]) {
      setError('الرجاء اختيار إجابة');
      return false;
    }
    return true;
  };

  const handleNext = () => {
    if (!validateStep()) return;
    if (isLastStep) {
      handleSubmit();
    } else {
      setCurrentStep(prev => prev + 1);
    }
  };

  const handleBack = () => {
    setCurrentStep(prev => prev - 1);
    setError('');
  };

  const handleSubmit = async () => {
    setLoading(true);
    setError('');
    setIsDuplicate(false);

    try {
      const payload = {
        ...formData,
        age: parseInt(formData.age, 10),
        email: formData.email?.trim() || null,
      };
      
      const response = await axios.post(`${BACKEND_URL}/survey/submit`, payload);

      if (response.data.success) {
        setDiscountCode(response.data.discountCode);
        setCurrentStep(questions.length);
      }
    } catch (err) {
      const errorMsg = err.response?.data?.message || '';

      if (errorMsg.includes('حصلت على كود خصم سابقاً') || errorMsg.includes('already')) {
        setIsDuplicate(true);
        setError('لقد شاركت سابقاً وحصلت على كود خصم. شكراً لثقتك فينا ❤️');
      } else {
        setError(errorMsg || 'حدث خطأ، الرجاء المحاولة مرة أخرى');
      }
    } finally {
      setLoading(false);
    }
  };

  const copyToClipboard = () => {
    navigator.clipboard.writeText(discountCode);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  const progress = ((currentStep + 1) / questions.length) * 100;

  // صفحة النجاح
  if (discountCode) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-[#fdfbf7] via-[#f5ede3] to-[#efcfa6]/40 flex items-center justify-center px-6 py-12">
        <div className="max-w-2xl w-full">
          <div className="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl p-8 md:p-12 text-center border-2 border-[#bb8d4f]/20">
            <div className="mb-6">
              <div className="w-20 h-20 bg-gradient-to-br from-[#2b0c10] to-[#bb8d4f] rounded-full flex items-center justify-center mx-auto mb-4">
                <Gift className="w-10 h-10 text-white" />
              </div>
              <h1 className="text-4xl font-bold text-[#2b0c10] mb-3 font-cairo">مبروك! 🎉</h1>
              <p className="text-xl text-[#2b0c10]/80 font-cairo">شكراً لمشاركتك! حصلت على خصم حصري</p>
            </div>

            <div className="bg-gradient-to-r from-[#2b0c10] to-[#bb8d4f] rounded-2xl p-8 mb-6">
              <p className="text-white/90 text-sm mb-3 font-cairo">كود الخصم الخاص بك:</p>
              <div className="bg-white/20 backdrop-blur-sm rounded-xl p-4 mb-4">
                <p className="text-4xl md:text-5xl font-bold text-white tracking-wider font-mono">{discountCode}</p>
              </div>
            </div>

            <Button onClick={copyToClipboard} className="w-full bg-white text-[#2b0c10] hover:bg-[#efcfa6] font-cairo font-bold text-lg py-6 rounded-xl mb-4">
              {copied ? <><Check className="w-5 h-5 ml-2" /> تم النسخ!</> : <><Copy className="w-5 h-5 ml-2" /> نسخ الكود</>}
            </Button>

            <Button onClick={() => navigate('/')} variant="outline" className="border-[#2b0c10] text-[#2b0c10] hover:bg-[#2b0c10] hover:text-white font-cairo">
              العودة للصفحة الرئيسية
            </Button>
          </div>
        </div>
      </div>
    );
  }

  // صفحة الاعتذار (إذا كان حاصل على خصم سابقاً)
  if (isDuplicate) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-[#fdfbf7] via-[#f5ede3] to-[#efcfa6]/40 flex items-center justify-center px-6 py-12">
        <div className="max-w-2xl w-full">
          <div className="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl p-8 md:p-12 text-center border-2 border-[#bb8d4f]/20">
            <AlertCircle className="w-16 h-16 text-amber-500 mx-auto mb-6" />
            <h1 className="text-3xl font-bold text-[#2b0c10] mb-3 font-cairo">شكراً لمشاركتك ❤️</h1>
            <p className="text-lg text-[#2b0c10]/80 font-cairo mb-8">
              لقد شاركت سابقاً وحصلت على كود خصم.<br/>
              لا يمكنك المشاركة مرة أخرى.
            </p>
            <Button onClick={() => navigate('/')} className="font-cairo">
              العودة للصفحة الرئيسية
            </Button>
          </div>
        </div>
      </div>
    );
  }

  // باقي الكود (الاستبيان الرئيسي)
  return (
    <div className="min-h-screen bg-gradient-to-br from-[#fdfbf7] via-[#f5ede3] to-[#efcfa6]/40 py-8 px-4">
      <div className="max-w-3xl mx-auto">
        {/* Header */}
        <div className="text-center mb-8">
          <div className="mb-4">
            <img 
              src="https://customer-assets.emergentagent.com/job_rouh-opening/artifacts/edg3av89_logos-1-removebg-preview.png"
              alt="Rouh Logo" 
              className="w-20 h-20 md:w-24 md:h-24 object-contain mx-auto"
            />
          </div>
          <h1 className="text-3xl md:text-4xl font-bold text-[#2b0c10] mb-3 font-cairo">
            احصل على خصم 20%
          </h1>
          <p className="text-lg text-[#2b0c10]/70 font-cairo">
            ساعدنا نفهم تفضيلاتك واحصل على خصم حصري
          </p>
        </div>

        {/* Progress Bar */}
        <div className="mb-8">
          <div className="flex justify-between items-center mb-2">
            <span className="text-sm text-[#2b0c10]/60 font-cairo">
              السؤال {currentStep + 1} من {questions.length}
            </span>
            <span className="text-sm text-[#2b0c10]/60 font-cairo">
              {Math.round(((currentStep + 1) / questions.length) * 100)}%
            </span>
          </div>
          <div className="h-2 bg-white/50 rounded-full overflow-hidden">
            <div className="h-full bg-gradient-to-r from-[#2b0c10] to-[#bb8d4f] transition-all duration-500" style={{ width: `${(currentStep + 1) / questions.length * 100}%` }} />
          </div>
        </div>

        {/* Question Card */}
        <div className="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl p-6 md:p-8 border-2 border-[#bb8d4f]/20">
          <div className="mb-6">
            <h2 className="text-2xl md:text-3xl font-bold text-[#2b0c10] mb-2 font-cairo">
              {currentQuestion.title}
            </h2>
            <p className="text-[#2b0c10]/70 font-cairo">
              {currentQuestion.subtitle}
            </p>
          </div>

          {currentQuestion.type === 'radio' && (
            <RadioGroup value={formData[currentQuestion.name]} onValueChange={(value) => handleRadioChange(currentQuestion.name, value)} className="space-y-3">
              {currentQuestion.options.map(option => (
                <div key={option.value} className="flex items-center gap-3 p-4 rounded-xl border-2 border-[#bb8d4f]/20 hover:border-[#bb8d4f] hover:bg-[#bb8d4f]/5 transition-all cursor-pointer" onClick={() => handleRadioChange(currentQuestion.name, option.value)}>
                  <RadioGroupItem value={option.value} id={option.value} />
                  <Label htmlFor={option.value} className="flex-1 cursor-pointer font-cairo text-lg">{option.label}</Label>
                </div>
              ))}
            </RadioGroup>
          )}

          {currentQuestion.fields && (
            <div className="space-y-4">
              {currentQuestion.fields.map(field => (
                <div key={field.name}>
                  <Label htmlFor={field.name} className="text-[#2b0c10] mb-2 block font-cairo">
                    {field.label} {field.required && <span className="text-red-500">*</span>}
                  </Label>
                  {field.type === 'textarea' ? (
                    <Textarea id={field.name} name={field.name} value={formData[field.name]} onChange={handleInputChange} placeholder={field.placeholder} className="min-h-[120px] font-cairo border-[#bb8d4f]/30 focus:border-[#bb8d4f]" dir="rtl" />
                  ) : (
                    <Input id={field.name} name={field.name} type={field.type} value={formData[field.name]} onChange={handleInputChange} placeholder={field.placeholder} className="font-cairo border-[#bb8d4f]/30 focus:border-[#bb8d4f]" dir={field.type === 'email' || field.type === 'tel' ? 'ltr' : 'rtl'} />
                  )}
                </div>
              ))}
            </div>
          )}

          {error && (
            <div className="mt-4 p-4 rounded-xl bg-red-50 border border-red-200">
              <p className="text-red-600 text-sm font-cairo">{error}</p>
            </div>
          )}

          <div className="flex gap-3 mt-6">
            {currentStep > 0 && (
              <Button onClick={handleBack} variant="outline" className="flex-1 border-[#2b0c10] text-[#2b0c10] hover:bg-[#2b0c10] hover:text-white font-cairo py-6 text-lg">
                <ArrowLeft className="w-5 h-5 ml-2" /> السابق
              </Button>
            )}
            <Button onClick={handleNext} className="flex-1 bg-gradient-to-r from-[#2b0c10] to-[#bb8d4f] text-white hover:from-[#bb8d4f] hover:to-[#d4a574] font-cairo py-6 text-lg" disabled={loading}>
              {loading ? 'جاري الحفظ...' : isLastStep ? <>احصل على الخصم <Gift className="w-5 h-5 mr-2" /></> : <>التالي <ArrowRight className="w-5 h-5 mr-2" /></>}
            </Button>
          </div>
        </div>

        <div className="text-center mt-6">
          <Button onClick={() => navigate('/')} variant="ghost" className="text-[#2b0c10]/60 hover:text-[#2b0c10] font-cairo">
            العودة للصفحة الرئيسية
          </Button>
        </div>
      </div>
    </div>
  );
};

export default Survey;