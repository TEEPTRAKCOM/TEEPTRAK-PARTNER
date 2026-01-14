import React, { useState, useEffect, createContext, useContext } from 'react';
import { LayoutDashboard, GraduationCap, FolderOpen, FileQuestion, FileSignature, Calendar, Settings, LogOut, Bell, Search, ChevronRight, CheckCircle, Circle, Play, Download, Award, MapPin, User, Globe, Menu, X, TrendingUp, Users, Target, Clock, ArrowRight, Star, Zap, Shield, FileText, DollarSign, BarChart3, ChevronDown, ExternalLink, Mail, Phone, Building, Briefcase, Filter, Eye, BookOpen, Trophy, CreditCard, PieChart, Activity, MessageCircle, Key, Lock, Copy, Check } from 'lucide-react';

// ============================================
// TRANSLATIONS - Scalable i18n System
// ============================================
const translations = {
  en: {
    common: { search: 'Search...', save: 'Save', cancel: 'Cancel', submit: 'Submit', download: 'Download', view: 'View', edit: 'Edit', delete: 'Delete', loading: 'Loading...', noData: 'No data available', copied: 'Copied!', copy: 'Copy' },
    nav: { dashboard: 'Dashboard', deals: 'Deal Registration', training: 'Training', academy: 'Academy Access', resources: 'Resources', quiz: 'Certification', agreement: 'Agreement', kickoff: 'Schedule Call', commissions: 'Commissions', analytics: 'Analytics', directory: 'Partner Directory', settings: 'Settings', logout: 'Sign Out', notifications: 'Notifications', chat: 'Chat with Teepy' },
    dashboard: { welcome: 'Welcome back', subtitle: "Here's your partner performance overview", progress: 'Onboarding Progress', tier: 'Partner Tier', region: 'Your Region', head: 'Regional Head', nextStep: 'Next Step', complete: 'Complete', viewAll: 'View All', quickActions: 'Quick Actions', recentActivity: 'Recent Activity', stats: { score: 'Partner Score', deals: 'Active Deals', revenue: 'Pipeline Value', commission: 'Earned Commission' } },
    deals: { title: 'Deal Registration', subtitle: 'Register and track your opportunities', newDeal: 'Register New Deal', pipeline: 'Your Pipeline', status: { registered: 'Registered', qualified: 'Qualified', proposal: 'Proposal', negotiation: 'Negotiation', closed_won: 'Closed Won', closed_lost: 'Closed Lost' }, protection: 'days protection left', value: 'Deal Value' },
    training: { title: 'Training Academy', subtitle: 'Master TeepTrak and boost your sales', progress: 'Your Progress', modules: 'Training Modules', completed: 'Completed', inProgress: 'In Progress', notStarted: 'Not Started', duration: 'min', start: 'Start Course', continue: 'Continue', review: 'Review', certificate: 'Get Certificate' },
    academy: { title: 'TeepTrak Academy Access', subtitle: 'Access comprehensive training materials and certifications', portalTitle: 'Academy Portal', portalDesc: 'Access the full TeepTrak Academy with advanced courses, certifications, and learning paths.', credentials: 'Your Credentials', username: 'Username', password: 'Password', accessPortal: 'Access Academy Portal', securityNote: 'Keep your credentials secure. Do not share them with others.', features: 'Academy Features', feature1: 'Product Deep Dives', feature2: 'Sales Methodology', feature3: 'Technical Certifications', feature4: 'Live Webinars' },
    resources: { title: 'Resource Center', subtitle: 'Sales tools, marketing assets, and documentation', categories: { all: 'All Resources', sales: 'Sales Materials', marketing: 'Marketing Assets', technical: 'Technical Docs', legal: 'Legal', templates: 'Templates' }, downloads: 'downloads', updated: 'Updated' },
    quiz: { title: 'Certification Quiz', subtitle: 'Validate your expertise and earn badges', start: 'Start Quiz', retake: 'Retake Quiz', question: 'Question', of: 'of', passed: 'Congratulations! You passed!', failed: 'Keep learning and try again', score: 'Your Score', passRate: 'Pass rate: 80%', timeLimit: 'Time limit', attempts: 'Attempts' },
    agreement: { title: 'Partner Agreement', subtitle: 'Review and sign your partnership terms', terms: 'Partnership Terms', sign: 'Sign Agreement', signed: 'Agreement Active', signedOn: 'Signed on', expires: 'Expires', benefits: 'Your Benefits', version: 'Version' },
    kickoff: { title: 'Schedule Meeting', subtitle: 'Book time with your Partnership Manager', agenda: 'Meeting Agenda', book: 'Book Meeting', booked: 'Meeting Scheduled', available: 'Available Slots', timezone: 'Your Timezone' },
    commissions: { title: 'Commission Center', subtitle: 'Track your earnings and payouts', balance: 'Available Balance', pending: 'Pending', paid: 'Total Paid', history: 'Payment History', withdraw: 'Request Withdrawal' },
    chat: { title: 'Chat with Teepy', subtitle: 'Your AI assistant for all TeepTrak questions', placeholder: 'Ask Teepy anything about TeepTrak...', send: 'Send' },
    tiers: { bronze: 'Bronze', silver: 'Silver', gold: 'Gold', platinum: 'Platinum' },
    onboarding: { application: 'Application', welcome: 'Welcome Video', training: 'Product Training', quiz: 'Certification', agreement: 'Sign Agreement', kickoff: 'Kickoff Call', firstDeal: 'First Deal' }
  },
  fr: {
    common: { search: 'Rechercher...', save: 'Enregistrer', cancel: 'Annuler', submit: 'Soumettre', download: 'Télécharger', view: 'Voir', edit: 'Modifier', delete: 'Supprimer', loading: 'Chargement...', noData: 'Aucune donnée', copied: 'Copié!', copy: 'Copier' },
    nav: { dashboard: 'Tableau de Bord', deals: 'Enregistrement Deals', training: 'Formation', academy: 'Accès Académie', resources: 'Ressources', quiz: 'Certification', agreement: 'Contrat', kickoff: 'Planifier Appel', commissions: 'Commissions', analytics: 'Analytiques', directory: 'Annuaire Partenaires', settings: 'Paramètres', logout: 'Déconnexion', notifications: 'Notifications', chat: 'Discuter avec Teepy' },
    dashboard: { welcome: 'Bienvenue', subtitle: 'Voici un aperçu de vos performances', progress: 'Progression Onboarding', tier: 'Niveau Partenaire', region: 'Votre Région', head: 'Responsable Régional', nextStep: 'Prochaine Étape', complete: 'Terminé', viewAll: 'Tout Voir', quickActions: 'Actions Rapides', recentActivity: 'Activité Récente', stats: { score: 'Score Partenaire', deals: 'Deals Actifs', revenue: 'Pipeline', commission: 'Commission Gagnée' } },
    deals: { title: 'Enregistrement de Deals', subtitle: 'Enregistrez et suivez vos opportunités', newDeal: 'Nouveau Deal', pipeline: 'Votre Pipeline', status: { registered: 'Enregistré', qualified: 'Qualifié', proposal: 'Proposition', negotiation: 'Négociation', closed_won: 'Gagné', closed_lost: 'Perdu' }, protection: 'jours de protection', value: 'Valeur du Deal' },
    training: { title: 'Académie de Formation', subtitle: 'Maîtrisez TeepTrak et boostez vos ventes', progress: 'Votre Progression', modules: 'Modules de Formation', completed: 'Terminé', inProgress: 'En Cours', notStarted: 'Non Commencé', duration: 'min', start: 'Commencer', continue: 'Continuer', review: 'Revoir', certificate: 'Obtenir Certificat' },
    academy: { title: 'Accès Académie TeepTrak', subtitle: 'Accédez aux formations complètes et certifications', portalTitle: 'Portail Académie', portalDesc: 'Accédez à l\'Académie TeepTrak complète avec des cours avancés, certifications et parcours d\'apprentissage.', credentials: 'Vos Identifiants', username: 'Nom d\'utilisateur', password: 'Mot de passe', accessPortal: 'Accéder au Portail', securityNote: 'Gardez vos identifiants confidentiels. Ne les partagez pas.', features: 'Fonctionnalités', feature1: 'Approfondissement Produit', feature2: 'Méthodologie de Vente', feature3: 'Certifications Techniques', feature4: 'Webinaires en Direct' },
    resources: { title: 'Centre de Ressources', subtitle: 'Outils de vente, assets marketing et documentation', categories: { all: 'Toutes les Ressources', sales: 'Outils de Vente', marketing: 'Assets Marketing', technical: 'Docs Techniques', legal: 'Juridique', templates: 'Modèles' }, downloads: 'téléchargements', updated: 'Mis à jour' },
    quiz: { title: 'Quiz de Certification', subtitle: 'Validez votre expertise et gagnez des badges', start: 'Commencer le Quiz', retake: 'Repasser le Quiz', question: 'Question', of: 'sur', passed: 'Félicitations! Vous avez réussi!', failed: 'Continuez à apprendre et réessayez', score: 'Votre Score', passRate: 'Seuil: 80%', timeLimit: 'Temps limite', attempts: 'Tentatives' },
    agreement: { title: 'Contrat Partenaire', subtitle: 'Consultez et signez vos conditions de partenariat', terms: 'Conditions du Partenariat', sign: 'Signer le Contrat', signed: 'Contrat Actif', signedOn: 'Signé le', expires: 'Expire le', benefits: 'Vos Avantages', version: 'Version' },
    kickoff: { title: 'Planifier une Réunion', subtitle: 'Réservez un créneau avec votre Responsable Partenariats', agenda: 'Agenda', book: 'Réserver', booked: 'Réunion Planifiée', available: 'Créneaux Disponibles', timezone: 'Votre Fuseau Horaire' },
    commissions: { title: 'Centre de Commissions', subtitle: 'Suivez vos gains et paiements', balance: 'Solde Disponible', pending: 'En Attente', paid: 'Total Payé', history: 'Historique des Paiements', withdraw: 'Demander un Retrait' },
    chat: { title: 'Discuter avec Teepy', subtitle: 'Votre assistant IA pour toutes vos questions TeepTrak', placeholder: 'Posez une question à Teepy...', send: 'Envoyer' },
    tiers: { bronze: 'Bronze', silver: 'Argent', gold: 'Or', platinum: 'Platine' },
    onboarding: { application: 'Candidature', welcome: 'Vidéo de Bienvenue', training: 'Formation Produit', quiz: 'Certification', agreement: 'Signature Contrat', kickoff: 'Appel Kickoff', firstDeal: 'Premier Deal' }
  },
  zh: {
    common: { search: '搜索...', save: '保存', cancel: '取消', submit: '提交', download: '下载', view: '查看', edit: '编辑', delete: '删除', loading: '加载中...', noData: '暂无数据', copied: '已复制!', copy: '复制' },
    nav: { dashboard: '仪表板', deals: '商机注册', training: '培训', academy: '学院入口', resources: '资源', quiz: '认证', agreement: '协议', kickoff: '预约会议', commissions: '佣金', analytics: '分析', directory: '合作伙伴目录', settings: '设置', logout: '退出', notifications: '通知', chat: '与Teepy聊天' },
    dashboard: { welcome: '欢迎回来', subtitle: '这是您的合作伙伴绩效概览', progress: '入职进度', tier: '合作伙伴等级', region: '您的区域', head: '区域负责人', nextStep: '下一步', complete: '完成', viewAll: '查看全部', quickActions: '快速操作', recentActivity: '最近活动', stats: { score: '合作伙伴评分', deals: '活跃商机', revenue: '管道价值', commission: '已获佣金' } },
    deals: { title: '商机注册', subtitle: '注册并跟踪您的商机', newDeal: '注册新商机', pipeline: '您的管道', status: { registered: '已注册', qualified: '已确认', proposal: '提案中', negotiation: '谈判中', closed_won: '已成交', closed_lost: '已失败' }, protection: '天保护期', value: '商机价值' },
    training: { title: '培训学院', subtitle: '掌握TeepTrak，提升销售业绩', progress: '您的进度', modules: '培训模块', completed: '已完成', inProgress: '进行中', notStarted: '未开始', duration: '分钟', start: '开始学习', continue: '继续', review: '复习', certificate: '获取证书' },
    academy: { title: 'TeepTrak学院入口', subtitle: '访问全面的培训材料和认证', portalTitle: '学院门户', portalDesc: '访问完整的TeepTrak学院，包含高级课程、认证和学习路径。', credentials: '您的凭证', username: '用户名', password: '密码', accessPortal: '访问学院门户', securityNote: '请妥善保管您的凭证，不要与他人分享。', features: '学院功能', feature1: '产品深度解析', feature2: '销售方法论', feature3: '技术认证', feature4: '直播网络研讨会' },
    resources: { title: '资源中心', subtitle: '销售工具、营销资产和文档', categories: { all: '所有资源', sales: '销售材料', marketing: '营销资产', technical: '技术文档', legal: '法律文件', templates: '模板' }, downloads: '次下载', updated: '更新于' },
    quiz: { title: '认证考试', subtitle: '验证您的专业知识并获得徽章', start: '开始考试', retake: '重新考试', question: '问题', of: '/', passed: '恭喜！您已通过！', failed: '继续学习后重试', score: '您的分数', passRate: '通过线：80%', timeLimit: '时间限制', attempts: '尝试次数' },
    agreement: { title: '合作伙伴协议', subtitle: '查看并签署您的合作条款', terms: '合作条款', sign: '签署协议', signed: '协议生效中', signedOn: '签署于', expires: '到期于', benefits: '您的权益', version: '版本' },
    kickoff: { title: '安排会议', subtitle: '与您的合作伙伴经理预约时间', agenda: '会议议程', book: '预约', booked: '已安排会议', available: '可用时段', timezone: '您的时区' },
    commissions: { title: '佣金中心', subtitle: '跟踪您的收入和付款', balance: '可用余额', pending: '待处理', paid: '已支付总额', history: '支付历史', withdraw: '申请提款' },
    chat: { title: '与Teepy聊天', subtitle: '您的TeepTrak AI助手', placeholder: '向Teepy提问...', send: '发送' },
    tiers: { bronze: '铜牌', silver: '银牌', gold: '金牌', platinum: '白金' },
    onboarding: { application: '申请', welcome: '欢迎视频', training: '产品培训', quiz: '认证考试', agreement: '签署协议', kickoff: '启动电话', firstDeal: '首个商机' }
  },
  de: {
    common: { search: 'Suchen...', save: 'Speichern', cancel: 'Abbrechen', submit: 'Absenden', download: 'Herunterladen', view: 'Ansehen', edit: 'Bearbeiten', delete: 'Löschen', loading: 'Laden...', noData: 'Keine Daten', copied: 'Kopiert!', copy: 'Kopieren' },
    nav: { dashboard: 'Dashboard', deals: 'Deal-Registrierung', training: 'Schulung', academy: 'Akademie-Zugang', resources: 'Ressourcen', quiz: 'Zertifizierung', agreement: 'Vereinbarung', kickoff: 'Termin buchen', commissions: 'Provisionen', analytics: 'Analysen', directory: 'Partnerverzeichnis', settings: 'Einstellungen', logout: 'Abmelden', notifications: 'Benachrichtigungen', chat: 'Chat mit Teepy' },
    dashboard: { welcome: 'Willkommen zurück', subtitle: 'Hier ist Ihre Partner-Performance-Übersicht', progress: 'Onboarding-Fortschritt', tier: 'Partner-Stufe', region: 'Ihre Region', head: 'Regionaler Leiter', nextStep: 'Nächster Schritt', complete: 'Abgeschlossen', viewAll: 'Alle anzeigen', quickActions: 'Schnellaktionen', recentActivity: 'Letzte Aktivität', stats: { score: 'Partner-Score', deals: 'Aktive Deals', revenue: 'Pipeline-Wert', commission: 'Verdiente Provision' } },
    academy: { title: 'TeepTrak Akademie-Zugang', subtitle: 'Zugang zu umfassenden Schulungsmaterialien und Zertifizierungen', portalTitle: 'Akademie-Portal', portalDesc: 'Zugang zur vollständigen TeepTrak Akademie mit fortgeschrittenen Kursen und Zertifizierungen.', credentials: 'Ihre Anmeldedaten', username: 'Benutzername', password: 'Passwort', accessPortal: 'Akademie-Portal öffnen', securityNote: 'Bewahren Sie Ihre Anmeldedaten sicher auf.', features: 'Akademie-Funktionen', feature1: 'Produkt-Vertiefung', feature2: 'Verkaufsmethodik', feature3: 'Technische Zertifizierungen', feature4: 'Live-Webinare' },
    chat: { title: 'Chat mit Teepy', subtitle: 'Ihr KI-Assistent für alle TeepTrak-Fragen', placeholder: 'Fragen Sie Teepy...', send: 'Senden' },
    tiers: { bronze: 'Bronze', silver: 'Silber', gold: 'Gold', platinum: 'Platin' },
    onboarding: { application: 'Bewerbung', welcome: 'Willkommensvideo', training: 'Produktschulung', quiz: 'Zertifizierung', agreement: 'Vereinbarung', kickoff: 'Kickoff-Anruf', firstDeal: 'Erster Deal' }
  },
  es: {
    common: { search: 'Buscar...', save: 'Guardar', cancel: 'Cancelar', submit: 'Enviar', download: 'Descargar', view: 'Ver', edit: 'Editar', delete: 'Eliminar', loading: 'Cargando...', noData: 'Sin datos', copied: '¡Copiado!', copy: 'Copiar' },
    nav: { dashboard: 'Panel', deals: 'Registro de Deals', training: 'Formación', academy: 'Acceso Academia', resources: 'Recursos', quiz: 'Certificación', agreement: 'Acuerdo', kickoff: 'Programar Llamada', commissions: 'Comisiones', analytics: 'Análisis', directory: 'Directorio de Socios', settings: 'Configuración', logout: 'Cerrar Sesión', notifications: 'Notificaciones', chat: 'Chat con Teepy' },
    dashboard: { welcome: 'Bienvenido', subtitle: 'Aquí está el resumen de tu rendimiento', progress: 'Progreso de Onboarding', tier: 'Nivel de Socio', region: 'Tu Región', head: 'Responsable Regional', nextStep: 'Siguiente Paso', complete: 'Completado', viewAll: 'Ver Todo', quickActions: 'Acciones Rápidas', recentActivity: 'Actividad Reciente', stats: { score: 'Puntuación', deals: 'Deals Activos', revenue: 'Valor Pipeline', commission: 'Comisión Ganada' } },
    academy: { title: 'Acceso Academia TeepTrak', subtitle: 'Accede a materiales de formación y certificaciones', portalTitle: 'Portal Academia', portalDesc: 'Accede a la Academia TeepTrak completa con cursos avanzados y certificaciones.', credentials: 'Tus Credenciales', username: 'Usuario', password: 'Contraseña', accessPortal: 'Acceder al Portal', securityNote: 'Mantén tus credenciales seguras.', features: 'Funcionalidades', feature1: 'Profundización de Producto', feature2: 'Metodología de Ventas', feature3: 'Certificaciones Técnicas', feature4: 'Webinars en Vivo' },
    chat: { title: 'Chat con Teepy', subtitle: 'Tu asistente IA para todas las preguntas de TeepTrak', placeholder: 'Pregunta a Teepy...', send: 'Enviar' },
    tiers: { bronze: 'Bronce', silver: 'Plata', gold: 'Oro', platinum: 'Platino' },
    onboarding: { application: 'Solicitud', welcome: 'Video de Bienvenida', training: 'Formación de Producto', quiz: 'Certificación', agreement: 'Firmar Acuerdo', kickoff: 'Llamada Kickoff', firstDeal: 'Primer Deal' }
  },
  pt: {
    common: { search: 'Pesquisar...', save: 'Salvar', cancel: 'Cancelar', submit: 'Enviar', download: 'Baixar', view: 'Ver', edit: 'Editar', delete: 'Excluir', loading: 'Carregando...', noData: 'Sem dados', copied: 'Copiado!', copy: 'Copiar' },
    nav: { dashboard: 'Painel', deals: 'Registro de Deals', training: 'Treinamento', academy: 'Acesso Academia', resources: 'Recursos', quiz: 'Certificação', agreement: 'Acordo', kickoff: 'Agendar Chamada', commissions: 'Comissões', analytics: 'Análises', directory: 'Diretório de Parceiros', settings: 'Configurações', logout: 'Sair', notifications: 'Notificações', chat: 'Chat com Teepy' },
    dashboard: { welcome: 'Bem-vindo', subtitle: 'Aqui está sua visão geral de desempenho', progress: 'Progresso de Onboarding', tier: 'Nível de Parceiro', region: 'Sua Região', head: 'Responsável Regional', nextStep: 'Próximo Passo', complete: 'Concluído', viewAll: 'Ver Tudo', quickActions: 'Ações Rápidas', recentActivity: 'Atividade Recente', stats: { score: 'Pontuação', deals: 'Deals Ativos', revenue: 'Valor do Pipeline', commission: 'Comissão Ganha' } },
    academy: { title: 'Acesso Academia TeepTrak', subtitle: 'Acesse materiais de treinamento e certificações', portalTitle: 'Portal Academia', portalDesc: 'Acesse a Academia TeepTrak completa com cursos avançados e certificações.', credentials: 'Suas Credenciais', username: 'Usuário', password: 'Senha', accessPortal: 'Acessar Portal', securityNote: 'Mantenha suas credenciais seguras.', features: 'Funcionalidades', feature1: 'Aprofundamento de Produto', feature2: 'Metodologia de Vendas', feature3: 'Certificações Técnicas', feature4: 'Webinars ao Vivo' },
    chat: { title: 'Chat com Teepy', subtitle: 'Seu assistente IA para todas as perguntas TeepTrak', placeholder: 'Pergunte ao Teepy...', send: 'Enviar' },
    tiers: { bronze: 'Bronze', silver: 'Prata', gold: 'Ouro', platinum: 'Platina' },
    onboarding: { application: 'Candidatura', welcome: 'Vídeo de Boas-vindas', training: 'Treinamento', quiz: 'Certificação', agreement: 'Assinar Acordo', kickoff: 'Chamada Kickoff', firstDeal: 'Primeiro Deal' }
  },
  ja: {
    common: { search: '検索...', save: '保存', cancel: 'キャンセル', submit: '送信', download: 'ダウンロード', view: '表示', edit: '編集', delete: '削除', loading: '読み込み中...', noData: 'データなし', copied: 'コピーしました!', copy: 'コピー' },
    nav: { dashboard: 'ダッシュボード', deals: '案件登録', training: 'トレーニング', academy: 'アカデミー', resources: 'リソース', quiz: '認定試験', agreement: '契約', kickoff: '会議予約', commissions: 'コミッション', analytics: '分析', directory: 'パートナー一覧', settings: '設定', logout: 'ログアウト', notifications: '通知', chat: 'Teepyとチャット' },
    dashboard: { welcome: 'おかえりなさい', subtitle: 'パートナー実績の概要', progress: 'オンボーディング進捗', tier: 'パートナーランク', region: 'リージョン', head: '地域担当', nextStep: '次のステップ', complete: '完了', viewAll: 'すべて表示', quickActions: 'クイックアクション', recentActivity: '最近のアクティビティ', stats: { score: 'スコア', deals: 'アクティブ案件', revenue: 'パイプライン', commission: '獲得コミッション' } },
    academy: { title: 'TeepTrakアカデミー', subtitle: '包括的なトレーニング資料と認定へのアクセス', portalTitle: 'アカデミーポータル', portalDesc: '高度なコースと認定を備えた完全なTeepTrakアカデミーにアクセス。', credentials: '認証情報', username: 'ユーザー名', password: 'パスワード', accessPortal: 'ポータルにアクセス', securityNote: '認証情報を安全に保管してください。', features: 'アカデミー機能', feature1: '製品詳細', feature2: '販売方法論', feature3: '技術認定', feature4: 'ライブウェビナー' },
    chat: { title: 'Teepyとチャット', subtitle: 'TeepTrakに関するすべての質問に対応するAIアシスタント', placeholder: 'Teepyに質問...', send: '送信' },
    tiers: { bronze: 'ブロンズ', silver: 'シルバー', gold: 'ゴールド', platinum: 'プラチナ' }
  }
};

// Language detection
const detectLanguage = () => {
  const supported = Object.keys(translations);
  const stored = typeof window !== 'undefined' ? localStorage.getItem('teeptrak_lang') : null;
  if (stored && supported.includes(stored)) return stored;
  
  const browserLangs = typeof navigator !== 'undefined' ? navigator.languages || [navigator.language] : ['en'];
  for (const lang of browserLangs) {
    const code = lang.split('-')[0].toLowerCase();
    if (supported.includes(code)) return code;
  }
  return 'en';
};

// Context
const AppContext = createContext();
const useApp = () => useContext(AppContext);
const useT = () => {
  const { lang } = useApp();
  return (path) => {
    const keys = path.split('.');
    let result = translations[lang] || translations.en;
    for (const key of keys) {
      result = result?.[key];
      if (!result) {
        let fallback = translations.en;
        for (const k of keys) fallback = fallback?.[k];
        return fallback || path;
      }
    }
    return result;
  };
};

// Mock Data
const mockPartner = { 
  id: '1', 
  name: 'Jean Dupont', 
  company: 'Actemium Bordeaux', 
  email: 'jean.dupont@actemium.com', 
  region: 'fr', 
  tier: 'gold', 
  score: 78, 
  currentStep: 5, 
  stats: { deals: 4, pipeline: 185000, commission: 24500 }, 
  onboarding: [true, true, true, true, false, false, false],
  academyCredentials: {
    username: 'jean.dupont@partner.teeptrak.net',
    password: 'TT-Partner-2025-JD'
  }
};

const mockDeals = [
  { id: 1, company: 'Stellantis Rennes', value: 45000, status: 'negotiation', protection: 67, contact: 'Marie Laurent' },
  { id: 2, company: 'Safran Aerospace', value: 72000, status: 'proposal', protection: 45, contact: 'Pierre Martin' },
  { id: 3, company: 'Danone Production', value: 38000, status: 'qualified', protection: 82, contact: 'Sophie Dubois' },
  { id: 4, company: 'L\'Oréal Factory', value: 30000, status: 'registered', protection: 90, contact: 'Lucas Bernard' }
];

const mockTraining = [
  { id: 1, title: 'Welcome to TeepTrak', duration: 5, status: 'completed', progress: 100, category: 'onboarding' },
  { id: 2, title: 'Product Deep Dive', duration: 25, status: 'completed', progress: 100, category: 'product' },
  { id: 3, title: 'OEE Fundamentals', duration: 20, status: 'completed', progress: 100, category: 'product' },
  { id: 4, title: 'Sales Methodology', duration: 30, status: 'in_progress', progress: 65, category: 'sales' },
  { id: 5, title: 'ROI Calculator Training', duration: 15, status: 'not_started', progress: 0, category: 'sales' },
  { id: 6, title: 'Technical Implementation', duration: 40, status: 'not_started', progress: 0, category: 'technical' }
];

const mockResources = [
  { id: 1, title: 'Sales Presentation 2025', category: 'sales', type: 'pptx', size: '5.2 MB', downloads: 234, icon: '📊' },
  { id: 2, title: 'Competitive Battlecards', category: 'sales', type: 'pdf', size: '1.8 MB', downloads: 189, icon: '⚔️' },
  { id: 3, title: 'ROI Calculator', category: 'sales', type: 'xlsx', size: '890 KB', downloads: 312, icon: '📈' },
  { id: 4, title: 'Brand Guidelines', category: 'marketing', type: 'pdf', size: '12 MB', downloads: 98, icon: '🎨' },
  { id: 5, title: 'Product Brochure', category: 'marketing', type: 'pdf', size: '4.5 MB', downloads: 267, icon: '📰' },
  { id: 6, title: 'Technical Specifications', category: 'technical', type: 'pdf', size: '2.1 MB', downloads: 156, icon: '⚙️' },
  { id: 7, title: 'Integration Guide', category: 'technical', type: 'pdf', size: '3.2 MB', downloads: 89, icon: '🔧' },
  { id: 8, title: 'Partner Agreement Template', category: 'legal', type: 'pdf', size: '450 KB', downloads: 45, icon: '📜' }
];

const mockCommissions = [
  { id: 1, deal: 'Renault Flins', amount: 8500, status: 'paid', date: '2025-12-15' },
  { id: 2, deal: 'Airbus Toulouse', amount: 12000, status: 'paid', date: '2025-11-20' },
  { id: 3, deal: 'Michelin Clermont', amount: 4000, status: 'pending', date: '2026-01-10' }
];

// Components
const TeepTrakLogo = ({ size = 'md' }) => {
  const sizes = { sm: 'h-6', md: 'h-8', lg: 'h-10' };
  return (
    <div className={`flex items-center gap-2 ${sizes[size]}`}>
      <div className="w-8 h-8 bg-[#eb352b] rounded-lg flex items-center justify-center relative overflow-hidden">
        <div className="absolute inset-0 flex items-center justify-center">
          <svg viewBox="0 0 32 32" className="w-6 h-6">
            <circle cx="16" cy="10" r="3" fill="white" opacity="0.9"/>
            <line x1="16" y1="13" x2="6" y2="26" stroke="white" strokeWidth="2" opacity="0.7"/>
            <line x1="16" y1="13" x2="26" y2="26" stroke="white" strokeWidth="2" opacity="0.7"/>
            <line x1="16" y1="13" x2="16" y2="28" stroke="white" strokeWidth="2" opacity="0.7"/>
            <line x1="16" y1="10" x2="8" y2="4" stroke="white" strokeWidth="2" opacity="0.7"/>
            <line x1="16" y1="10" x2="24" y2="4" stroke="white" strokeWidth="2" opacity="0.7"/>
          </svg>
        </div>
      </div>
      <div className="flex items-baseline">
        <span className="text-xl font-bold text-[#232120]">teep</span>
        <span className="text-xl font-bold text-[#eb352b]">trak</span>
      </div>
    </div>
  );
};

const Card = ({ children, className = '', hover = false, onClick }) => (
  <div onClick={onClick} className={`bg-white rounded-2xl border border-gray-100 ${hover ? 'hover:shadow-lg hover:border-gray-200 cursor-pointer transition-all duration-200' : 'shadow-sm'} ${className}`}>
    {children}
  </div>
);

const TierBadge = ({ tier }) => {
  const t = useT();
  const colors = { bronze: 'bg-amber-100 text-amber-700 border-amber-200', silver: 'bg-gray-100 text-gray-700 border-gray-200', gold: 'bg-yellow-100 text-yellow-700 border-yellow-200', platinum: 'bg-violet-100 text-violet-700 border-violet-200' };
  const icons = { bronze: '🥉', silver: '🥈', gold: '🥇', platinum: '💎' };
  return (
    <span className={`inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold border ${colors[tier]}`}>
      <span>{icons[tier]}</span>
      {t(`tiers.${tier}`)}
    </span>
  );
};

const ProgressRing = ({ progress, size = 80 }) => {
  const r = (size - 8) / 2;
  const c = r * 2 * Math.PI;
  const offset = c - (progress / 100) * c;
  return (
    <div className="relative" style={{ width: size, height: size }}>
      <svg className="transform -rotate-90" width={size} height={size}>
        <circle cx={size/2} cy={size/2} r={r} stroke="#f1f5f9" strokeWidth="6" fill="none" />
        <circle cx={size/2} cy={size/2} r={r} stroke="#eb352b" strokeWidth="6" fill="none" strokeLinecap="round" strokeDasharray={c} strokeDashoffset={offset} className="transition-all duration-700" />
      </svg>
      <div className="absolute inset-0 flex items-center justify-center">
        <span className="text-lg font-bold text-[#232120]">{progress}%</span>
      </div>
    </div>
  );
};

const StatCard = ({ icon: Icon, label, value, suffix, trend, color = 'bg-red-50 text-[#eb352b]' }) => (
  <Card className="p-5">
    <div className="flex items-start justify-between">
      <div className={`w-10 h-10 rounded-xl ${color} flex items-center justify-center`}>
        <Icon className="w-5 h-5" />
      </div>
      {trend && <span className={`text-xs font-medium px-2 py-1 rounded-full ${trend > 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600'}`}>{trend > 0 ? '+' : ''}{trend}%</span>}
    </div>
    <div className="mt-4">
      <div className="text-2xl font-bold text-[#232120]">{value}{suffix}</div>
      <div className="text-sm text-gray-500 mt-1">{label}</div>
    </div>
  </Card>
);

const LanguageSwitcher = () => {
  const { lang, setLang } = useApp();
  const [open, setOpen] = useState(false);
  const langs = [
    { code: 'en', name: 'English', flag: '🇬🇧' },
    { code: 'fr', name: 'Français', flag: '🇫🇷' },
    { code: 'zh', name: '中文', flag: '🇨🇳' },
    { code: 'de', name: 'Deutsch', flag: '🇩🇪' },
    { code: 'es', name: 'Español', flag: '🇪🇸' },
    { code: 'pt', name: 'Português', flag: '🇵🇹' },
    { code: 'ja', name: '日本語', flag: '🇯🇵' }
  ];
  const current = langs.find(l => l.code === lang) || langs[0];
  
  return (
    <div className="relative">
      <button onClick={() => setOpen(!open)} className="flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
        <span className="text-lg">{current.flag}</span>
        <span className="text-sm font-medium text-gray-700">{current.code.toUpperCase()}</span>
        <ChevronDown className="w-4 h-4 text-gray-400" />
      </button>
      {open && (
        <>
          <div className="fixed inset-0 z-40" onClick={() => setOpen(false)} />
          <div className="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
            {langs.map(l => (
              <button key={l.code} onClick={() => { setLang(l.code); setOpen(false); }} className={`w-full flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors ${lang === l.code ? 'bg-red-50' : ''}`}>
                <span className="text-lg">{l.flag}</span>
                <span className="text-sm font-medium text-gray-700">{l.name}</span>
                {lang === l.code && <CheckCircle className="w-4 h-4 text-[#eb352b] ml-auto" />}
              </button>
            ))}
          </div>
        </>
      )}
    </div>
  );
};

// Teepy Chatbot Component
const TeepyChatbot = ({ isOpen, onClose }) => {
  const t = useT();
  
  if (!isOpen) return null;
  
  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
      <div className="bg-white rounded-2xl shadow-2xl w-full max-w-2xl h-[600px] flex flex-col overflow-hidden">
        <div className="flex items-center justify-between p-4 border-b border-gray-100 bg-gradient-to-r from-[#eb352b] to-[#ff674c]">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
              <MessageCircle className="w-5 h-5 text-white" />
            </div>
            <div>
              <h3 className="font-semibold text-white">{t('chat.title')}</h3>
              <p className="text-xs text-white/80">{t('chat.subtitle')}</p>
            </div>
          </div>
          <button onClick={onClose} className="p-2 hover:bg-white/20 rounded-lg transition-colors">
            <X className="w-5 h-5 text-white" />
          </button>
        </div>
        
        {/* Iframe for Teepy chatbot - replace URL with your actual chatbot URL */}
        <div className="flex-1 relative">
          <iframe
            src="https://teepy.teeptrak.com"
            className="w-full h-full border-0"
            title="Teepy Chatbot"
            allow="microphone"
          />
          {/* Fallback message if iframe doesn't load */}
          <div className="absolute inset-0 flex items-center justify-center bg-gray-50 pointer-events-none opacity-0">
            <div className="text-center p-8">
              <MessageCircle className="w-12 h-12 text-gray-300 mx-auto mb-4" />
              <p className="text-gray-500">Loading Teepy...</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

// Floating Teepy Button
const TeepyFloatingButton = ({ onClick }) => {
  const t = useT();
  return (
    <button
      onClick={onClick}
      className="fixed bottom-6 right-6 z-40 flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-[#eb352b] to-[#ff674c] text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group"
    >
      <MessageCircle className="w-5 h-5" />
      <span className="font-medium hidden sm:inline">{t('nav.chat')}</span>
      <span className="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white animate-pulse" />
    </button>
  );
};

// Sidebar
const Sidebar = ({ currentPage, setCurrentPage, isOpen, setIsOpen }) => {
  const t = useT();
  const navItems = [
    { id: 'dashboard', icon: LayoutDashboard, label: t('nav.dashboard') },
    { id: 'deals', icon: Target, label: t('nav.deals') },
    { id: 'training', icon: GraduationCap, label: t('nav.training') },
    { id: 'academy', icon: BookOpen, label: t('nav.academy'), badge: 'NEW' },
    { id: 'resources', icon: FolderOpen, label: t('nav.resources') },
    { id: 'quiz', icon: Trophy, label: t('nav.quiz') },
    { id: 'agreement', icon: FileSignature, label: t('nav.agreement') },
    { id: 'kickoff', icon: Calendar, label: t('nav.kickoff') },
    { id: 'commissions', icon: DollarSign, label: t('nav.commissions') }
  ];

  return (
    <>
      {isOpen && <div className="lg:hidden fixed inset-0 bg-black/20 z-40" onClick={() => setIsOpen(false)} />}
      <aside className={`fixed lg:static inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-100 flex flex-col transform transition-transform duration-300 ${isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}`}>
        <div className="p-6 border-b border-gray-100">
          <div className="flex items-center justify-between">
            <TeepTrakLogo />
            <button className="lg:hidden p-2 hover:bg-gray-100 rounded-lg" onClick={() => setIsOpen(false)}><X className="w-5 h-5 text-gray-500" /></button>
          </div>
          <div className="mt-1 text-xs text-gray-400 font-medium">Partner Portal</div>
        </div>
        
        <nav className="flex-1 p-4 space-y-1 overflow-y-auto">
          {navItems.map(item => (
            <button key={item.id} onClick={() => { setCurrentPage(item.id); setIsOpen(false); }} className={`w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all ${currentPage === item.id ? 'bg-red-50 text-[#eb352b] font-medium' : 'text-gray-600 hover:bg-gray-50'}`}>
              <item.icon className="w-5 h-5" />
              <span className="flex-1 text-left">{item.label}</span>
              {item.badge && (
                <span className="px-2 py-0.5 bg-[#eb352b] text-white text-xs font-bold rounded-full">{item.badge}</span>
              )}
            </button>
          ))}
        </nav>
        
        <div className="p-4 border-t border-gray-100">
          <div className="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
            <div className="w-10 h-10 bg-[#eb352b] rounded-xl flex items-center justify-center text-white font-bold text-sm">
              {mockPartner.name.split(' ').map(n => n[0]).join('')}
            </div>
            <div className="flex-1 min-w-0">
              <div className="text-sm font-semibold text-[#232120] truncate">{mockPartner.name}</div>
              <div className="text-xs text-gray-500 truncate">{mockPartner.company}</div>
            </div>
          </div>
          <button className="w-full flex items-center gap-3 px-4 py-3 mt-2 rounded-xl text-gray-600 hover:bg-gray-50 transition-colors">
            <LogOut className="w-5 h-5" />
            <span>{t('nav.logout')}</span>
          </button>
        </div>
      </aside>
    </>
  );
};

// Header
const Header = ({ setIsOpen }) => {
  const t = useT();
  return (
    <header className="sticky top-0 z-30 bg-white/80 backdrop-blur-lg border-b border-gray-100 px-4 lg:px-8 py-4">
      <div className="flex items-center gap-4">
        <button className="lg:hidden p-2 hover:bg-gray-100 rounded-xl" onClick={() => setIsOpen(true)}><Menu className="w-5 h-5 text-gray-600" /></button>
        <div className="flex-1 max-w-md relative">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
          <input type="text" placeholder={t('common.search')} className="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#eb352b] focus:ring-1 focus:ring-[#eb352b]/20 transition-all" />
        </div>
        <div className="flex items-center gap-2">
          <LanguageSwitcher />
          <button className="relative p-2.5 hover:bg-gray-100 rounded-xl transition-colors">
            <Bell className="w-5 h-5 text-gray-600" />
            <span className="absolute top-1 right-1 w-2 h-2 bg-[#eb352b] rounded-full" />
          </button>
        </div>
      </div>
    </header>
  );
};

// Dashboard Page
const DashboardPage = ({ setCurrentPage }) => {
  const t = useT();
  const steps = ['application', 'welcome', 'training', 'quiz', 'agreement', 'kickoff', 'firstDeal'];
  const completedSteps = mockPartner.onboarding.filter(Boolean).length;
  const progress = Math.round((completedSteps / steps.length) * 100);

  return (
    <div className="space-y-8">
      <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-bold text-[#232120]">{t('dashboard.welcome')}, {mockPartner.name.split(' ')[0]}! 👋</h1>
          <p className="text-gray-500 mt-1">{t('dashboard.subtitle')}</p>
        </div>
        <TierBadge tier={mockPartner.tier} />
      </div>

      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard icon={Star} label={t('dashboard.stats.score')} value={mockPartner.score} suffix=" pts" trend={12} />
        <StatCard icon={Target} label={t('dashboard.stats.deals')} value={mockPartner.stats.deals} trend={25} color="bg-blue-50 text-blue-600" />
        <StatCard icon={TrendingUp} label={t('dashboard.stats.revenue')} value={`€${(mockPartner.stats.pipeline/1000).toFixed(0)}K`} trend={18} color="bg-emerald-50 text-emerald-600" />
        <StatCard icon={DollarSign} label={t('dashboard.stats.commission')} value={`€${(mockPartner.stats.commission/1000).toFixed(1)}K`} trend={32} color="bg-violet-50 text-violet-600" />
      </div>

      <div className="grid lg:grid-cols-3 gap-6">
        <Card className="lg:col-span-2 p-6">
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-lg font-semibold text-[#232120]">{t('dashboard.progress')}</h2>
            <ProgressRing progress={progress} />
          </div>
          <div className="space-y-3">
            {steps.map((step, i) => {
              const done = mockPartner.onboarding[i];
              const current = !done && (i === 0 || mockPartner.onboarding[i-1]);
              return (
                <div key={step} className={`flex items-center gap-4 p-3 rounded-xl transition-all ${done ? 'bg-green-50' : current ? 'bg-red-50 border border-[#eb352b]/20' : 'bg-gray-50 opacity-60'}`}>
                  {done ? <CheckCircle className="w-5 h-5 text-green-500" /> : <Circle className={`w-5 h-5 ${current ? 'text-[#eb352b]' : 'text-gray-300'}`} />}
                  <span className={`flex-1 text-sm ${done ? 'text-green-700' : current ? 'text-[#eb352b] font-medium' : 'text-gray-500'}`}>{t(`onboarding.${step}`)}</span>
                  {current && <span className="text-xs bg-[#eb352b] text-white px-2 py-1 rounded-full font-medium">{t('dashboard.nextStep')}</span>}
                </div>
              );
            })}
          </div>
        </Card>

        <Card className="p-6">
          <h2 className="text-lg font-semibold text-[#232120] mb-4">{t('dashboard.quickActions')}</h2>
          <div className="space-y-3">
            {[
              { icon: Target, label: 'Register Deal', color: 'bg-red-50 text-[#eb352b]', page: 'deals' },
              { icon: BookOpen, label: 'Access Academy', color: 'bg-purple-50 text-purple-600', page: 'academy' },
              { icon: GraduationCap, label: 'Continue Training', color: 'bg-blue-50 text-blue-600', page: 'training' },
              { icon: FolderOpen, label: 'Download Resources', color: 'bg-emerald-50 text-emerald-600', page: 'resources' }
            ].map((action, i) => (
              <button key={i} onClick={() => setCurrentPage(action.page)} className="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors text-left">
                <div className={`w-10 h-10 rounded-xl ${action.color} flex items-center justify-center`}>
                  <action.icon className="w-5 h-5" />
                </div>
                <span className="text-sm font-medium text-[#232120]">{action.label}</span>
                <ChevronRight className="w-4 h-4 text-gray-400 ml-auto" />
              </button>
            ))}
          </div>
        </Card>
      </div>

      <Card className="p-6">
        <div className="flex items-center justify-between mb-4">
          <h2 className="text-lg font-semibold text-[#232120]">{t('deals.pipeline')}</h2>
          <button onClick={() => setCurrentPage('deals')} className="text-sm text-[#eb352b] font-medium hover:underline">{t('dashboard.viewAll')}</button>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="text-left text-xs text-gray-500 border-b border-gray-100">
                <th className="pb-3 font-medium">Company</th>
                <th className="pb-3 font-medium">Value</th>
                <th className="pb-3 font-medium">Status</th>
                <th className="pb-3 font-medium">Protection</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-50">
              {mockDeals.map(deal => (
                <tr key={deal.id} className="hover:bg-gray-50 transition-colors">
                  <td className="py-3">
                    <div className="font-medium text-[#232120]">{deal.company}</div>
                    <div className="text-xs text-gray-500">{deal.contact}</div>
                  </td>
                  <td className="py-3 font-semibold text-[#232120]">€{deal.value.toLocaleString()}</td>
                  <td className="py-3">
                    <span className={`text-xs font-medium px-2.5 py-1 rounded-full ${
                      deal.status === 'negotiation' ? 'bg-amber-100 text-amber-700' :
                      deal.status === 'proposal' ? 'bg-blue-100 text-blue-700' :
                      deal.status === 'qualified' ? 'bg-purple-100 text-purple-700' :
                      'bg-gray-100 text-gray-700'
                    }`}>{t(`deals.status.${deal.status}`)}</span>
                  </td>
                  <td className="py-3 text-sm text-gray-600">{deal.protection} {t('deals.protection')}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </Card>
    </div>
  );
};

// Academy Access Page
const AcademyPage = () => {
  const t = useT();
  const [showPassword, setShowPassword] = useState(false);
  const [copiedField, setCopiedField] = useState(null);
  
  const copyToClipboard = (text, field) => {
    navigator.clipboard.writeText(text);
    setCopiedField(field);
    setTimeout(() => setCopiedField(null), 2000);
  };
  
  const features = [
    { icon: BookOpen, label: t('academy.feature1'), desc: '15+ in-depth courses' },
    { icon: Target, label: t('academy.feature2'), desc: 'Sales certification path' },
    { icon: Award, label: t('academy.feature3'), desc: 'Expert-level badges' },
    { icon: Users, label: t('academy.feature4'), desc: 'Weekly sessions' }
  ];

  return (
    <div className="space-y-8 max-w-4xl mx-auto">
      <div>
        <h1 className="text-2xl font-bold text-[#232120]">{t('academy.title')}</h1>
        <p className="text-gray-500 mt-1">{t('academy.subtitle')}</p>
      </div>

      <div className="grid md:grid-cols-2 gap-6">
        {/* Credentials Card */}
        <Card className="p-6">
          <div className="flex items-center gap-3 mb-6">
            <div className="w-12 h-12 bg-[#eb352b]/10 rounded-xl flex items-center justify-center">
              <Key className="w-6 h-6 text-[#eb352b]" />
            </div>
            <div>
              <h2 className="text-lg font-semibold text-[#232120]">{t('academy.credentials')}</h2>
              <p className="text-sm text-gray-500">academy.teeptrak.net</p>
            </div>
          </div>

          <div className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">{t('academy.username')}</label>
              <div className="flex items-center gap-2">
                <div className="flex-1 p-3 bg-gray-50 rounded-xl font-mono text-sm text-[#232120] border border-gray-100">
                  {mockPartner.academyCredentials.username}
                </div>
                <button 
                  onClick={() => copyToClipboard(mockPartner.academyCredentials.username, 'username')}
                  className="p-3 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-100 transition-colors"
                >
                  {copiedField === 'username' ? <Check className="w-5 h-5 text-green-500" /> : <Copy className="w-5 h-5 text-gray-500" />}
                </button>
              </div>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">{t('academy.password')}</label>
              <div className="flex items-center gap-2">
                <div className="flex-1 p-3 bg-gray-50 rounded-xl font-mono text-sm text-[#232120] border border-gray-100 flex items-center justify-between">
                  <span>{showPassword ? mockPartner.academyCredentials.password : '••••••••••••••••'}</span>
                  <button onClick={() => setShowPassword(!showPassword)} className="text-gray-400 hover:text-gray-600">
                    <Eye className="w-4 h-4" />
                  </button>
                </div>
                <button 
                  onClick={() => copyToClipboard(mockPartner.academyCredentials.password, 'password')}
                  className="p-3 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-100 transition-colors"
                >
                  {copiedField === 'password' ? <Check className="w-5 h-5 text-green-500" /> : <Copy className="w-5 h-5 text-gray-500" />}
                </button>
              </div>
            </div>
          </div>

          <div className="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-100">
            <div className="flex items-start gap-3">
              <Lock className="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" />
              <p className="text-sm text-amber-700">{t('academy.securityNote')}</p>
            </div>
          </div>

          <a 
            href="https://academy.teeptrak.net" 
            target="_blank" 
            rel="noopener noreferrer"
            className="mt-6 w-full py-4 bg-[#eb352b] text-white rounded-xl font-semibold hover:bg-[#d42d24] transition-colors flex items-center justify-center gap-2"
          >
            <ExternalLink className="w-5 h-5" />
            {t('academy.accessPortal')}
          </a>
        </Card>

        {/* Features Card */}
        <Card className="p-6">
          <h2 className="text-lg font-semibold text-[#232120] mb-6">{t('academy.features')}</h2>
          <div className="space-y-4">
            {features.map((feature, i) => (
              <div key={i} className="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                <div className="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                  <feature.icon className="w-6 h-6 text-[#eb352b]" />
                </div>
                <div>
                  <div className="font-medium text-[#232120]">{feature.label}</div>
                  <div className="text-sm text-gray-500">{feature.desc}</div>
                </div>
              </div>
            ))}
          </div>
        </Card>
      </div>

      {/* Academy Preview */}
      <Card className="p-6">
        <h2 className="text-lg font-semibold text-[#232120] mb-4">{t('academy.portalTitle')}</h2>
        <p className="text-gray-500 mb-6">{t('academy.portalDesc')}</p>
        
        <div className="bg-gray-100 rounded-xl overflow-hidden aspect-video flex items-center justify-center relative">
          <div className="absolute inset-0 bg-gradient-to-br from-[#eb352b]/5 to-[#ff674c]/5" />
          <div className="text-center z-10">
            <div className="w-20 h-20 bg-white rounded-2xl shadow-lg flex items-center justify-center mx-auto mb-4">
              <GraduationCap className="w-10 h-10 text-[#eb352b]" />
            </div>
            <h3 className="text-xl font-bold text-[#232120] mb-2">TeepTrak Academy</h3>
            <p className="text-gray-500 mb-4">Comprehensive training platform</p>
            <a 
              href="https://academy.teeptrak.net" 
              target="_blank" 
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 px-6 py-3 bg-[#eb352b] text-white rounded-xl font-medium hover:bg-[#d42d24] transition-colors"
            >
              <Play className="w-5 h-5" />
              Launch Academy
            </a>
          </div>
        </div>
      </Card>
    </div>
  );
};

// Training Page
const TrainingPage = () => {
  const t = useT();
  const completed = mockTraining.filter(m => m.status === 'completed').length;
  
  return (
    <div className="space-y-8">
      <div>
        <h1 className="text-2xl font-bold text-[#232120]">{t('training.title')}</h1>
        <p className="text-gray-500 mt-1">{t('training.subtitle')}</p>
      </div>

      <Card className="p-6">
        <div className="flex items-center justify-between mb-4">
          <span className="text-gray-500">{t('training.progress')}</span>
          <span className="font-semibold text-[#232120]">{completed}/{mockTraining.length} {t('training.completed')}</span>
        </div>
        <div className="h-3 bg-gray-100 rounded-full overflow-hidden">
          <div className="h-full bg-[#eb352b] rounded-full transition-all duration-500" style={{ width: `${(completed/mockTraining.length)*100}%` }} />
        </div>
      </Card>

      <div className="space-y-4">
        {mockTraining.map(module => (
          <Card key={module.id} className="p-5 hover:shadow-md transition-shadow" hover>
            <div className="flex items-center gap-5">
              <div className={`w-14 h-14 rounded-xl flex items-center justify-center ${
                module.status === 'completed' ? 'bg-green-100 text-green-600' :
                module.status === 'in_progress' ? 'bg-[#eb352b]/10 text-[#eb352b]' :
                'bg-gray-100 text-gray-400'
              }`}>
                {module.status === 'completed' ? <CheckCircle className="w-7 h-7" /> : <Play className="w-7 h-7" />}
              </div>
              <div className="flex-1">
                <h3 className="font-semibold text-[#232120]">{module.title}</h3>
                <div className="flex items-center gap-3 text-sm text-gray-500 mt-1">
                  <span className="flex items-center gap-1"><Clock className="w-4 h-4" />{module.duration} {t('training.duration')}</span>
                  {module.status === 'in_progress' && <span className="text-[#eb352b] font-medium">{module.progress}%</span>}
                </div>
              </div>
              <button className={`px-5 py-2.5 rounded-xl font-medium transition-all ${
                module.status === 'completed' ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' :
                'bg-[#eb352b] text-white hover:bg-[#d42d24]'
              }`}>
                {module.status === 'completed' ? t('training.review') : module.status === 'in_progress' ? t('training.continue') : t('training.start')}
              </button>
            </div>
          </Card>
        ))}
      </div>
    </div>
  );
};

// Resources Page
const ResourcesPage = () => {
  const t = useT();
  const [category, setCategory] = useState('all');
  const categories = ['all', 'sales', 'marketing', 'technical', 'legal'];
  const filtered = category === 'all' ? mockResources : mockResources.filter(r => r.category === category);

  return (
    <div className="space-y-8">
      <div>
        <h1 className="text-2xl font-bold text-[#232120]">{t('resources.title')}</h1>
        <p className="text-gray-500 mt-1">{t('resources.subtitle')}</p>
      </div>

      <div className="flex gap-2 flex-wrap">
        {categories.map(cat => (
          <button key={cat} onClick={() => setCategory(cat)} className={`px-4 py-2 rounded-xl text-sm font-medium transition-all ${category === cat ? 'bg-[#eb352b] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'}`}>
            {t(`resources.categories.${cat}`)}
          </button>
        ))}
      </div>

      <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        {filtered.map(resource => (
          <Card key={resource.id} className="p-5" hover>
            <div className="flex items-start gap-4">
              <div className="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center text-2xl">{resource.icon}</div>
              <div className="flex-1 min-w-0">
                <h3 className="font-semibold text-[#232120] truncate">{resource.title}</h3>
                <p className="text-sm text-gray-500">{resource.type.toUpperCase()} • {resource.size}</p>
                <p className="text-xs text-gray-400 mt-1">{resource.downloads} {t('resources.downloads')}</p>
              </div>
            </div>
            <button className="w-full mt-4 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl font-medium text-gray-700 transition-all flex items-center justify-center gap-2">
              <Download className="w-4 h-4" />
              {t('common.download')}
            </button>
          </Card>
        ))}
      </div>
    </div>
  );
};

// Commissions Page
const CommissionsPage = () => {
  const t = useT();
  const balance = 4000;
  const pending = 4000;
  const paid = 20500;

  return (
    <div className="space-y-8">
      <div>
        <h1 className="text-2xl font-bold text-[#232120]">{t('commissions.title')}</h1>
        <p className="text-gray-500 mt-1">{t('commissions.subtitle')}</p>
      </div>

      <div className="grid md:grid-cols-3 gap-4">
        <Card className="p-6 bg-gradient-to-br from-[#eb352b] to-[#ff674c] text-white">
          <DollarSign className="w-8 h-8 mb-4 opacity-80" />
          <div className="text-3xl font-bold">€{balance.toLocaleString()}</div>
          <div className="text-sm opacity-80 mt-1">{t('commissions.balance')}</div>
          <button className="mt-4 w-full py-2.5 bg-white/20 hover:bg-white/30 rounded-xl font-medium transition-colors">{t('commissions.withdraw')}</button>
        </Card>
        <Card className="p-6">
          <Clock className="w-8 h-8 text-amber-500 mb-4" />
          <div className="text-3xl font-bold text-[#232120]">€{pending.toLocaleString()}</div>
          <div className="text-sm text-gray-500 mt-1">{t('commissions.pending')}</div>
        </Card>
        <Card className="p-6">
          <CheckCircle className="w-8 h-8 text-green-500 mb-4" />
          <div className="text-3xl font-bold text-[#232120]">€{paid.toLocaleString()}</div>
          <div className="text-sm text-gray-500 mt-1">{t('commissions.paid')}</div>
        </Card>
      </div>

      <Card className="p-6">
        <h2 className="text-lg font-semibold text-[#232120] mb-4">{t('commissions.history')}</h2>
        <div className="space-y-3">
          {mockCommissions.map(c => (
            <div key={c.id} className="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
              <div>
                <div className="font-medium text-[#232120]">{c.deal}</div>
                <div className="text-sm text-gray-500">{c.date}</div>
              </div>
              <div className="text-right">
                <div className="font-bold text-[#232120]">€{c.amount.toLocaleString()}</div>
                <span className={`text-xs font-medium px-2 py-1 rounded-full ${c.status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'}`}>
                  {c.status === 'paid' ? 'Paid' : 'Pending'}
                </span>
              </div>
            </div>
          ))}
        </div>
      </Card>
    </div>
  );
};

// Simple placeholder pages
const DealsPage = () => {
  const t = useT();
  return (
    <div className="space-y-8">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-[#232120]">{t('deals.title')}</h1>
          <p className="text-gray-500 mt-1">{t('deals.subtitle')}</p>
        </div>
        <button className="px-5 py-2.5 bg-[#eb352b] text-white rounded-xl font-medium hover:bg-[#d42d24] transition-colors flex items-center gap-2">
          <Target className="w-5 h-5" />
          {t('deals.newDeal')}
        </button>
      </div>
      <div className="grid gap-4">
        {mockDeals.map(deal => (
          <Card key={deal.id} className="p-5" hover>
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-4">
                <div className="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                  <Building className="w-6 h-6 text-gray-500" />
                </div>
                <div>
                  <h3 className="font-semibold text-[#232120]">{deal.company}</h3>
                  <p className="text-sm text-gray-500">{deal.contact}</p>
                </div>
              </div>
              <div className="text-right">
                <div className="text-xl font-bold text-[#232120]">€{deal.value.toLocaleString()}</div>
                <div className="text-sm text-gray-500">{deal.protection} {t('deals.protection')}</div>
              </div>
            </div>
          </Card>
        ))}
      </div>
    </div>
  );
};

const QuizPage = () => {
  const t = useT();
  return (
    <div className="max-w-2xl mx-auto text-center py-12">
      <div className="w-20 h-20 bg-[#eb352b]/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
        <Trophy className="w-10 h-10 text-[#eb352b]" />
      </div>
      <h1 className="text-2xl font-bold text-[#232120] mb-2">{t('quiz.title')}</h1>
      <p className="text-gray-500 mb-8">{t('quiz.subtitle')}</p>
      <Card className="p-8 text-left">
        <div className="flex items-center gap-4 mb-6">
          <div className="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
            <FileQuestion className="w-6 h-6 text-blue-600" />
          </div>
          <div>
            <div className="font-semibold text-[#232120]">TeepTrak Certification</div>
            <div className="text-sm text-gray-500">20 questions • {t('quiz.passRate')}</div>
          </div>
        </div>
        <button className="w-full py-4 bg-[#eb352b] text-white rounded-xl font-semibold hover:bg-[#d42d24] transition-colors flex items-center justify-center gap-2">
          {t('quiz.start')}
          <ArrowRight className="w-5 h-5" />
        </button>
      </Card>
    </div>
  );
};

const AgreementPage = () => {
  const t = useT();
  const terms = ['20-30% commission on closed deals', 'Net 30 payment terms', 'Non-exclusive partnership', '90-day deal protection period', '1 year term with auto-renewal'];
  return (
    <div className="max-w-3xl mx-auto space-y-8">
      <div>
        <h1 className="text-2xl font-bold text-[#232120]">{t('agreement.title')}</h1>
        <p className="text-gray-500 mt-1">{t('agreement.subtitle')}</p>
      </div>
      <Card className="p-8">
        <div className="flex items-center gap-4 mb-6">
          <div className="w-14 h-14 bg-[#eb352b]/10 rounded-xl flex items-center justify-center">
            <FileSignature className="w-7 h-7 text-[#eb352b]" />
          </div>
          <div>
            <h2 className="text-xl font-semibold text-[#232120]">{t('agreement.terms')}</h2>
            <p className="text-sm text-gray-500">{t('agreement.version')} 2.1 - January 2026</p>
          </div>
        </div>
        <div className="space-y-3 mb-8">
          {terms.map((term, i) => (
            <div key={i} className="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
              <CheckCircle className="w-5 h-5 text-green-500 flex-shrink-0" />
              <span className="text-gray-700">{term}</span>
            </div>
          ))}
        </div>
        <button className="w-full py-4 bg-[#eb352b] text-white rounded-xl font-semibold hover:bg-[#d42d24] transition-colors flex items-center justify-center gap-2">
          <FileSignature className="w-5 h-5" />
          {t('agreement.sign')}
        </button>
      </Card>
    </div>
  );
};

const KickoffPage = () => {
  const t = useT();
  const agenda = ['Introductions (5 min)', 'Your goals review (5 min)', 'Live product demo (10 min)', 'Partnership details (5 min)', 'Next steps & Q&A (5 min)'];
  return (
    <div className="max-w-3xl mx-auto space-y-8">
      <div>
        <h1 className="text-2xl font-bold text-[#232120]">{t('kickoff.title')}</h1>
        <p className="text-gray-500 mt-1">{t('kickoff.subtitle')}</p>
      </div>
      <div className="grid md:grid-cols-2 gap-6">
        <Card className="p-6 text-center">
          <div className="w-16 h-16 bg-[#eb352b] rounded-2xl flex items-center justify-center text-white font-bold text-xl mx-auto mb-4">GA</div>
          <h3 className="font-semibold text-[#232120]">Guillaume Artigue</h3>
          <p className="text-sm text-gray-500">Head of Partnerships - Francophone</p>
          <p className="text-sm text-[#eb352b] mt-2">guillaume.artigue@teeptrak.com</p>
        </Card>
        <Card className="p-6">
          <h3 className="font-semibold text-[#232120] mb-4">{t('kickoff.agenda')}</h3>
          <div className="space-y-2">
            {agenda.map((item, i) => (
              <div key={i} className="flex items-center gap-3 text-sm text-gray-600">
                <div className="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-xs font-medium">{i+1}</div>
                {item}
              </div>
            ))}
          </div>
        </Card>
      </div>
      <Card className="p-8 text-center">
        <Calendar className="w-12 h-12 text-[#eb352b] mx-auto mb-4" />
        <h3 className="text-lg font-semibold text-[#232120] mb-2">Select a Time</h3>
        <p className="text-gray-500 mb-6">30-minute kickoff call</p>
        <button className="px-8 py-4 bg-[#eb352b] text-white rounded-xl font-semibold hover:bg-[#d42d24] transition-colors">
          {t('kickoff.book')}
        </button>
      </Card>
    </div>
  );
};

// Main App
export default function App() {
  const [lang, setLang] = useState('en');
  const [currentPage, setCurrentPage] = useState('dashboard');
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [chatOpen, setChatOpen] = useState(false);

  useEffect(() => {
    const detected = detectLanguage();
    setLang(detected);
  }, []);

  useEffect(() => {
    if (typeof window !== 'undefined') {
      localStorage.setItem('teeptrak_lang', lang);
    }
  }, [lang]);

  const pages = {
    dashboard: () => <DashboardPage setCurrentPage={setCurrentPage} />,
    deals: DealsPage,
    training: TrainingPage,
    academy: AcademyPage,
    resources: ResourcesPage,
    quiz: QuizPage,
    agreement: AgreementPage,
    kickoff: KickoffPage,
    commissions: CommissionsPage
  };

  const CurrentPage = pages[currentPage] || (() => <DashboardPage setCurrentPage={setCurrentPage} />);

  return (
    <AppContext.Provider value={{ lang, setLang }}>
      <div className="min-h-screen bg-[#f8fafc]">
        <div className="flex">
          <Sidebar currentPage={currentPage} setCurrentPage={setCurrentPage} isOpen={sidebarOpen} setIsOpen={setSidebarOpen} />
          <div className="flex-1 flex flex-col min-h-screen">
            <Header setIsOpen={setSidebarOpen} />
            <main className="flex-1 p-4 lg:p-8">
              <CurrentPage />
            </main>
          </div>
        </div>
        
        {/* Teepy Floating Button */}
        <TeepyFloatingButton onClick={() => setChatOpen(true)} />
        
        {/* Teepy Chatbot Modal */}
        <TeepyChatbot isOpen={chatOpen} onClose={() => setChatOpen(false)} />
      </div>
    </AppContext.Provider>
  );
}
