# 📚 Article API Documentation

Une API REST moderne construite avec Laravel 12 pour la gestion d'articles.

## 🚀 Démarrage rapide

### Prérequis
- PHP 8.2+
- Composer
- SQLite

### Installation

```bash
# Cloner le projet
git clone <votre-repo>
cd article-api

# Installer les dépendances
composer install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Exécuter les migrations
php artisan migrate

# Lancer le serveur de développement
php artisan serve
```

L'API sera disponible sur `http://localhost:8000`

## 📋 Endpoints de l'API

Base URL: `http://localhost:8000/api`

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/article` | Récupérer tous les articles |
| GET | `/article/{id}` | Récupérer un article spécifique |
| POST | `/article` | Créer un nouvel article |
| PUT | `/article/{id}` | Modifier un article existant |
| DELETE | `/article/{id}` | Supprimer un article |

## 📊 Structure des données

### Modèle Article

```json
{
  "id": 1,
  "title": "Titre de l'article",
  "content": "Contenu de l'article...",
  "published": true,
  "created_at": "2025-07-29T15:30:00.000000Z",
  "updated_at": "2025-07-29T15:30:00.000000Z"
}
```

### Champs requis pour création/modification
- `title` (string, max: 255 caractères) - **Obligatoire**
- `content` (text) - **Obligatoire**
- `published` (boolean) - Optionnel, défaut: false

## 🔧 Exemples d'utilisation JavaScript

### 1. Récupérer tous les articles

```javascript
async function getAllArticles() {
    try {
        const response = await fetch('http://localhost:8000/api/article', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            console.log('Articles récupérés:', data.articles);
            return data.articles;
        }
    } catch (error) {
        console.error('Erreur lors de la récupération des articles:', error);
    }
}

// Utilisation
getAllArticles().then(articles => {
    articles.forEach(article => {
        console.log(`${article.title}: ${article.published ? 'Publié' : 'Brouillon'}`);
    });
});
```

### 2. Récupérer un article spécifique

```javascript
async function getArticle(articleId) {
    try {
        const response = await fetch(`http://localhost:8000/api/article/${articleId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            console.log('Article trouvé:', data.article);
            return data.article;
        }
    } catch (error) {
        console.error('Erreur lors de la récupération de l\'article:', error);
    }
}

// Utilisation
getArticle(1).then(article => {
    if (article) {
        document.getElementById('article-title').textContent = article.title;
        document.getElementById('article-content').textContent = article.content;
    }
});
```### 3. Créer un nouvel article

```javascript
async function createArticle(articleData) {
    try {
        const response = await fetch('http://localhost:8000/api/article', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(articleData)
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            console.log('Article créé avec succès:', data.article);
            return data.article;
        } else {
            console.error('Erreur lors de la création:', data);
            return null;
        }
    } catch (error) {
        console.error('Erreur réseau:', error);
    }
}

// Utilisation
const newArticle = {
    title: "Mon Premier Article",
    content: "Ceci est le contenu de mon premier article via l'API.",
    published: false
};

createArticle(newArticle).then(article => {
    if (article) {
        console.log(`Article créé avec l'ID: ${article.id}`);
        // Rediriger vers la page d'édition ou afficher un message de succès
        showSuccessMessage('Article créé avec succès !');
    }
});

// Exemple avec validation des données
function validateArticleData(data) {
    const errors = [];
    
    if (!data.title || data.title.trim() === '') {
        errors.push('Le titre est obligatoire');
    }
    
    if (data.title && data.title.length > 255) {
        errors.push('Le titre ne peut pas dépasser 255 caractères');
    }
    
    if (!data.content || data.content.trim() === '') {
        errors.push('Le contenu est obligatoire');
    }
    
    return errors;
}

// Utilisation avec validation
async function createArticleWithValidation(formData) {
    const errors = validateArticleData(formData);
    
    if (errors.length > 0) {
        console.error('Erreurs de validation:', errors);
        displayErrors(errors);
        return;
    }
    
    const article = await createArticle(formData);
    return article;
}
```

### 4. Modifier un article existant

```javascript
async function updateArticle(articleId, updatedData) {
    try {
        const response = await fetch(`http://localhost:8000/api/article/${articleId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(updatedData)
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            console.log('Article mis à jour:', data.article);
            return data.article;
        } else {
            console.error('Erreur lors de la mise à jour:', data);
            return null;
        }
    } catch (error) {
        console.error('Erreur réseau:', error);
    }
}

// Utilisation
const updatedData = {
    title: "Titre Modifié",
    content: "Contenu mis à jour avec de nouvelles informations.",
    published: true
};

updateArticle(1, updatedData).then(article => {
    if (article) {
        console.log('Mise à jour réussie');
        // Actualiser l'affichage
        refreshArticleDisplay(article);
    }
});

// Exemple de modification partielle (seulement le statut de publication)
async function togglePublishStatus(articleId, currentStatus) {
    const updatedData = {
        published: !currentStatus
    };
    
    // Note: L'API actuelle requiert tous les champs, 
    // il faudrait d'abord récupérer l'article complet
    const currentArticle = await getArticle(articleId);
    if (currentArticle) {
        const fullUpdateData = {
            title: currentArticle.title,
            content: currentArticle.content,
            published: !currentStatus
        };
        
        return await updateArticle(articleId, fullUpdateData);
    }
}
```### 5. Supprimer un article

```javascript
async function deleteArticle(articleId) {
    try {
        const response = await fetch(`http://localhost:8000/api/article/${articleId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            console.log('Article supprimé:', data.message);
            return true;
        } else {
            console.error('Erreur lors de la suppression:', data);
            return false;
        }
    } catch (error) {
        console.error('Erreur réseau:', error);
        return false;
    }
}

// Utilisation avec confirmation
async function deleteArticleWithConfirmation(articleId, articleTitle) {
    const confirmed = confirm(`Êtes-vous sûr de vouloir supprimer l'article "${articleTitle}" ?`);
    
    if (confirmed) {
        const success = await deleteArticle(articleId);
        
        if (success) {
            // Retirer l'article de l'interface
            document.getElementById(`article-${articleId}`)?.remove();
            showSuccessMessage('Article supprimé avec succès');
        } else {
            showErrorMessage('Erreur lors de la suppression');
        }
        
        return success;
    }
    
    return false;
}
```

## 🛠️ Classe utilitaire pour l'API

```javascript
class ArticleAPI {
    constructor(baseURL = 'http://localhost:8000/api') {
        this.baseURL = baseURL;
        this.defaultHeaders = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
    }
    
    async request(endpoint, options = {}) {
        try {
            const url = `${this.baseURL}${endpoint}`;
            const config = {
                headers: this.defaultHeaders,
                ...options
            };
            
            const response = await fetch(url, config);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${data.message || 'Erreur inconnue'}`);
            }
            
            return data;
        } catch (error) {
            console.error('Erreur API:', error);
            throw error;
        }
    }
    
    // Récupérer tous les articles
    async getAll() {
        const data = await this.request('/article');
        return data.success ? data.articles : [];
    }
    
    // Récupérer un article par ID
    async getById(id) {
        const data = await this.request(`/article/${id}`);
        return data.success ? data.article : null;
    }
    
    // Créer un nouvel article
    async create(articleData) {
        const data = await this.request('/article', {
            method: 'POST',
            body: JSON.stringify(articleData)
        });
        return data.success ? data.article : null;
    }
    
    // Mettre à jour un article
    async update(id, articleData) {
        const data = await this.request(`/article/${id}`, {
            method: 'PUT',
            body: JSON.stringify(articleData)
        });
        return data.success ? data.article : null;
    }
    
    // Supprimer un article
    async delete(id) {
        const data = await this.request(`/article/${id}`, {
            method: 'DELETE'
        });
        return data.success;
    }
}

// Utilisation de la classe
const api = new ArticleAPI();

// Exemples d'utilisation
async function exempleUtilisation() {
    try {
        // Créer un article
        const newArticle = await api.create({
            title: "Article via classe API",
            content: "Contenu créé avec la classe utilitaire",
            published: true
        });
        
        console.log('Nouvel article créé:', newArticle);
        
        // Récupérer tous les articles
        const articles = await api.getAll();
        console.log('Tous les articles:', articles);
        
        // Mettre à jour l'article
        if (newArticle) {
            const updated = await api.update(newArticle.id, {
                title: "Titre mis à jour",
                content: newArticle.content,
                published: false
            });
            console.log('Article mis à jour:', updated);
        }
        
    } catch (error) {
        console.error('Erreur dans l\'exemple:', error);
    }
}
```## 📱 Exemple d'interface complète

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionnaire d'Articles</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .article { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .article.published { border-left: 4px solid #28a745; }
        .article.draft { border-left: 4px solid #ffc107; }
        button { margin: 5px; padding: 8px 12px; border: none; border-radius: 3px; cursor: pointer; }
        .btn-primary { background: #007bff; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-success { background: #28a745; color: white; }
        form { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 3px; }
        textarea { height: 100px; resize: vertical; }
    </style>
</head>
<body>
    <h1>🚀 Gestionnaire d'Articles</h1>
    
    <!-- Formulaire de création/modification -->
    <form id="articleForm">
        <h3 id="formTitle">Créer un nouvel article</h3>
        <input type="hidden" id="articleId" value="">
        
        <label for="title">Titre:</label>
        <input type="text" id="title" name="title" required maxlength="255">
        
        <label for="content">Contenu:</label>
        <textarea id="content" name="content" required></textarea>
        
        <label>
            <input type="checkbox" id="published" name="published"> Publier immédiatement
        </label>
        
        <div>
            <button type="submit" class="btn-primary" id="submitBtn">Créer l'article</button>
            <button type="button" class="btn-secondary" id="cancelBtn" onclick="resetForm()">Annuler</button>
        </div>
    </form>
    
    <!-- Liste des articles -->
    <div id="articlesContainer">
        <h3>📚 Liste des articles</h3>
        <button onclick="loadArticles()" class="btn-success">Actualiser</button>
        <div id="articlesList"></div>
    </div>

    <script>
        // Initialiser l'API
        const api = new ArticleAPI();
        
        // Charger les articles au démarrage
        document.addEventListener('DOMContentLoaded', function() {
            loadArticles();
        });
        
        // Gestionnaire de formulaire
        document.getElementById('articleForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const articleData = {
                title: formData.get('title'),
                content: formData.get('content'),
                published: formData.has('published')
            };
            
            const articleId = document.getElementById('articleId').value;
            
            try {
                if (articleId) {
                    // Modification
                    await api.update(articleId, articleData);
                    showMessage('Article mis à jour avec succès!', 'success');
                } else {
                    // Création
                    await api.create(articleData);
                    showMessage('Article créé avec succès!', 'success');
                }
                
                resetForm();
                loadArticles();
            } catch (error) {
                showMessage('Erreur: ' + error.message, 'error');
            }
        });
        
        // Charger et afficher tous les articles
        async function loadArticles() {
            try {
                const articles = await api.getAll();
                displayArticles(articles);
            } catch (error) {
                showMessage('Erreur lors du chargement: ' + error.message, 'error');
            }
        }
        
        // Afficher les articles dans l'interface
        function displayArticles(articles) {
            const container = document.getElementById('articlesList');
            
            if (articles.length === 0) {
                container.innerHTML = '<p>Aucun article trouvé.</p>';
                return;
            }
            
            container.innerHTML = articles.map(article => `
                <div class="article ${article.published ? 'published' : 'draft'}" id="article-${article.id}">
                    <h4>${escapeHtml(article.title)}</h4>
                    <p>${escapeHtml(article.content.substring(0, 150))}${article.content.length > 150 ? '...' : ''}</p>
                    <small>
                        Status: <strong>${article.published ? 'Publié' : 'Brouillon'}</strong> | 
                        Créé: ${new Date(article.created_at).toLocaleDateString('fr-FR')}
                    </small>
                    <div>
                        <button onclick="editArticle(${article.id})" class="btn-primary">Modifier</button>
                        <button onclick="togglePublish(${article.id}, ${article.published})" class="btn-secondary">
                            ${article.published ? 'Dépublier' : 'Publier'}
                        </button>
                        <button onclick="deleteArticleConfirm(${article.id}, '${escapeHtml(article.title)}')" class="btn-danger">
                            Supprimer
                        </button>
                    </div>
                </div>
            `).join('');
        }
        
        // Modifier un article
        async function editArticle(id) {
            try {
                const article = await api.getById(id);
                if (article) {
                    document.getElementById('articleId').value = article.id;
                    document.getElementById('title').value = article.title;
                    document.getElementById('content').value = article.content;
                    document.getElementById('published').checked = article.published;
                    document.getElementById('formTitle').textContent = 'Modifier l\'article';
                    document.getElementById('submitBtn').textContent = 'Mettre à jour';
                    
                    // Scroller vers le formulaire
                    document.getElementById('articleForm').scrollIntoView({ behavior: 'smooth' });
                }
            } catch (error) {
                showMessage('Erreur lors du chargement de l\'article: ' + error.message, 'error');
            }
        }
        
        // Basculer le statut de publication
        async function togglePublish(id, currentStatus) {
            try {
                const article = await api.getById(id);
                if (article) {
                    await api.update(id, {
                        title: article.title,
                        content: article.content,
                        published: !currentStatus
                    });
                    
                    showMessage(`Article ${!currentStatus ? 'publié' : 'dépublié'} avec succès!`, 'success');
                    loadArticles();
                }
            } catch (error) {
                showMessage('Erreur lors de la modification: ' + error.message, 'error');
            }
        }
        
        // Supprimer un article avec confirmation
        async function deleteArticleConfirm(id, title) {
            if (confirm(`Êtes-vous sûr de vouloir supprimer l'article "${title}" ?`)) {
                try {
                    await api.delete(id);
                    showMessage('Article supprimé avec succès!', 'success');
                    loadArticles();
                } catch (error) {
                    showMessage('Erreur lors de la suppression: ' + error.message, 'error');
                }
            }
        }
        
        // Réinitialiser le formulaire
        function resetForm() {
            document.getElementById('articleForm').reset();
            document.getElementById('articleId').value = '';
            document.getElementById('formTitle').textContent = 'Créer un nouvel article';
            document.getElementById('submitBtn').textContent = 'Créer l\'article';
        }
        
        // Afficher un message à l'utilisateur
        function showMessage(message, type = 'info') {
            const messageDiv = document.createElement('div');
            messageDiv.textContent = message;
            messageDiv.style.cssText = `
                position: fixed; top: 20px; right: 20px; z-index: 1000;
                padding: 15px; border-radius: 5px; color: white; font-weight: bold;
                background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
            `;
            
            document.body.appendChild(messageDiv);
            
            setTimeout(() => {
                document.body.removeChild(messageDiv);
            }, 3000);
        }
        
        // Échapper le HTML pour éviter les injections XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Inclure la classe ArticleAPI ici (copiée du code précédent)
        class ArticleAPI {
            // ... (code de la classe comme défini précédemment)
        }
    </script>
</body>
</html>
```

```javascript
class DebugArticleAPI extends ArticleAPI {
    constructor(baseURL, debug = false) {
        super(baseURL);
        this.debug = debug;
        this.requestCount = 0;
    }
    
    async request(endpoint, options = {}) {
        this.requestCount++;
        const requestId = `req_${this.requestCount}`;
        
        if (this.debug) {
            console.group(`🔍 API Request ${requestId}`);
            console.log('Endpoint:', endpoint);
            console.log('Method:', options.method || 'GET');
            console.log('Headers:', options.headers);
            if (options.body) {
                console.log('Body:', JSON.parse(options.body));
            }
            console.time(`Request ${requestId}`);
        }
        
        try {
            const result = await super.request(endpoint, options);
            
            if (this.debug) {
                console.log('✅ Success:', result);
                console.timeEnd(`Request ${requestId}`);
                console.groupEnd();
            }
            
            return result;
        } catch (error) {
            if (this.debug) {
                console.error('❌ Error:', error.message);
                console.timeEnd(`Request ${requestId}`);
                console.groupEnd();
            }
            throw error;
        }
    }
    
    getStats() {
        return {
            totalRequests: this.requestCount,
            cacheSize: this.cache?.size || 0
        };
    }
}

// Utilisation
const api = new DebugArticleAPI('http://localhost:8000/api', true);
```

## 📊 Métriques et monitoring

```javascript
class MetricsArticleAPI extends ArticleAPI {
    constructor(baseURL) {
        super(baseURL);
        this.metrics = {
            requests: {
                total: 0,
                success: 0,
                errors: 0
            },
            timing: {
                average: 0,
                min: Infinity,
                max: 0,
                total: 0
            },
            endpoints: {}
        };
    }
    
    async request(endpoint, options = {}) {
        const startTime = performance.now();
        const method = options.method || 'GET';
        const key = `${method} ${endpoint}`;
        
        // Initialiser les métriques pour cet endpoint
        if (!this.metrics.endpoints[key]) {
            this.metrics.endpoints[key] = { calls: 0, errors: 0, avgTime: 0 };
        }
        
        this.metrics.requests.total++;
        this.metrics.endpoints[key].calls++;
        
        try {
            const result = await super.request(endpoint, options);
            
            // Mesurer le temps de réponse
            const duration = performance.now() - startTime;
            this.updateTiming(duration);
            this.metrics.endpoints[key].avgTime = 
                (this.metrics.endpoints[key].avgTime + duration) / 2;
            
            this.metrics.requests.success++;
            
            return result;
        } catch (error) {
            this.metrics.requests.errors++;
            this.metrics.endpoints[key].errors++;
            throw error;
        }
    }
    
    updateTiming(duration) {
        this.metrics.timing.total += duration;
        this.metrics.timing.min = Math.min(this.metrics.timing.min, duration);
        this.metrics.timing.max = Math.max(this.metrics.timing.max, duration);
        this.metrics.timing.average = this.metrics.timing.total / this.metrics.requests.total;
    }
    
    getMetrics() {
        return {
            ...this.metrics,
            successRate: (this.metrics.requests.success / this.metrics.requests.total * 100).toFixed(2) + '%'
        };
    }
    
    resetMetrics() {
        this.metrics = {
            requests: { total: 0, success: 0, errors: 0 },
            timing: { average: 0, min: Infinity, max: 0, total: 0 },
            endpoints: {}
        };
    }
}
```

## 🔧 Configuration avancée

```javascript
// config/api.js
const API_CONFIG = {
    development: {
        baseURL: 'http://localhost:8000/api',
        timeout: 5000,
        retries: 3,
        debug: true
    },
    production: {
        baseURL: 'https://api.monsite.com/api',
        timeout: 10000,
        retries: 2,
        debug: false
    },
    test: {
        baseURL: 'http://test-api.local/api',
        timeout: 2000,
        retries: 1,
        debug: false
    }
};

class ConfigurableArticleAPI extends ArticleAPI {
    constructor(environment = 'development') {
        const config = API_CONFIG[environment];
        super(config.baseURL);
        
        this.config = config;
        this.timeout = config.timeout;
        this.maxRetries = config.retries;
        this.debug = config.debug;
    }
    
    async request(endpoint, options = {}) {
        // Ajouter timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), this.timeout);
        
        const requestOptions = {
            ...options,
            signal: controller.signal
        };
        
        try {
            const result = await this.withRetry(
                () => super.request(endpoint, requestOptions),
                this.maxRetries
            );
            
            clearTimeout(timeoutId);
            return result;
        } catch (error) {
            clearTimeout(timeoutId);
            
            if (error.name === 'AbortError') {
                throw new Error(`Timeout après ${this.timeout}ms`);
            }
            
            throw error;
        }
    }
    
    async withRetry(fn, maxRetries) {
        for (let i = 0; i <= maxRetries; i++) {
            try {
                return await fn();
            } catch (error) {
                if (i === maxRetries || !this.shouldRetry(error)) {
                    throw error;
                }
                
                const delay = Math.pow(2, i) * 1000; // Backoff exponentiel
                await new Promise(resolve => setTimeout(resolve, delay));
            }
        }
    }
    
    shouldRetry(error) {
        // Retry seulement pour certains types d'erreurs
        return error.message.includes('500') || 
               error.message.includes('503') || 
               error.name === 'AbortError';
    }
}
```

## 📚 Exemples d'intégration

### Avec React

```jsx
// hooks/useArticles.js
import { useState, useEffect } from 'react';
import { ArticleAPI } from '../api/articleAPI';

const api = new ArticleAPI();

export function useArticles() {
    const [articles, setArticles] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    
    const loadArticles = async () => {
        setLoading(true);
        setError(null);
        
        try {
            const data = await api.getAll();
            setArticles(data);
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };
    
    const createArticle = async (articleData) => {
        try {
            const newArticle = await api.create(articleData);
            setArticles(prev => [...prev, newArticle]);
            return newArticle;
        } catch (err) {
            setError(err.message);
            throw err;
        }
    };
    
    const updateArticle = async (id, articleData) => {
        try {
            const updated = await api.update(id, articleData);
            setArticles(prev => 
                prev.map(article => 
                    article.id === id ? updated : article
                )
            );
            return updated;
        } catch (err) {
            setError(err.message);
            throw err;
        }
    };
    
    const deleteArticle = async (id) => {
        try {
            await api.delete(id);
            setArticles(prev => prev.filter(article => article.id !== id));
        } catch (err) {
            setError(err.message);
            throw err;
        }
    };
    
    useEffect(() => {
        loadArticles();
    }, []);
    
    return {
        articles,
        loading,
        error,
        loadArticles,
        createArticle,
        updateArticle,
        deleteArticle
    };
}

// Composant React
function ArticleList() {
    const { articles, loading, error, deleteArticle } = useArticles();
    
    if (loading) return <div>Chargement...</div>;
    if (error) return <div>Erreur: {error}</div>;
    
    return (
        <div>
            {articles.map(article => (
                <div key={article.id} className="article-card">
                    <h3>{article.title}</h3>
                    <p>{article.content.substring(0, 100)}...</p>
                    <button onClick={() => deleteArticle(article.id)}>
                        Supprimer
                    </button>
                </div>
            ))}
        </div>
    );
}
```

### Avec Vue.js

```javascript
// composables/useArticles.js
import { ref, onMounted } from 'vue';
import { ArticleAPI } from '../api/articleAPI';

export function useArticles() {
    const articles = ref([]);
    const loading = ref(false);
    const error = ref(null);
    const api = new ArticleAPI();
    
    const loadArticles = async () => {
        loading.value = true;
        error.value = null;
        
        try {
            articles.value = await api.getAll();
        } catch (err) {
            error.value = err.message;
        } finally {
            loading.value = false;
        }
    };
    
    const createArticle = async (articleData) => {
        const newArticle = await api.create(articleData);
        articles.value.push(newArticle);
        return newArticle;
    };
    
    onMounted(loadArticles);
    
    return {
        articles,
        loading,
        error,
        loadArticles,
        createArticle
    };
}
```

## 🚀 Déploiement et production

### Variables d'environnement

```bash
# .env.production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://monapi.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=articles_prod
DB_USERNAME=root
DB_PASSWORD=motdepasse_securise

SANCTUM_STATEFUL_DOMAINS=monsite.com,www.monsite.com
SESSION_DOMAIN=.monsite.com
```

### Configuration CORS pour production

```php
// config/cors.php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => [
    'https://monsite.com',
    'https://www.monsite.com',
    // Ne jamais utiliser '*' en production
],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,
```

## 📖 Ressources supplémentaires

- [Documentation Laravel](https://laravel.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [API RESTful Best Practices](https://restfulapi.net/)
- [HTTP Status Codes](https://httpstatuses.com/)

## 🤝 Contribution

Pour contribuer à ce projet :

1. Forkez le repository
2. Créez une branche pour votre fonctionnalité
3. Commitez vos changements
4. Poussez vers la branche
5. Ouvrez une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

---

**Développé avec ❤️ et Laravel**

Pour toute question ou support, n'hésitez pas à ouvrir une issue sur GitHub.