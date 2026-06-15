<p align="center">
  <strong>VH.</strong><br/>
  <em>Portfolio Pessoal — Vinicius Henrique</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2"/>
  <img src="https://img.shields.io/badge/MVC-Custom_Framework-A855F7?style=for-the-badge" alt="MVC"/>
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"/>
  <img src="https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite"/>
  <img src="https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker"/>
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript"/>
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white" alt="CSS3"/>
</p>

---

## 📋 Sumário

- [Sobre o Projeto](#-sobre-o-projeto)
- [Demonstração](#demonstração)
- [Arquitetura e Estrutura](#-arquitetura-e-estrutura)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [Funcionalidades](#-funcionalidades)
- [Boas Práticas Aplicadas](#-boas-práticas-aplicadas)
- [Segurança](#-segurança)
- [SEO e Performance](#-seo-e-performance)
- [Painel Administrativo](#-painel-administrativo)
- [Como Executar](#-como-executar)
- [Variáveis de Ambiente](#-variáveis-de-ambiente)
- [Contato](#-contato)

---

## 🎯 Sobre o Projeto

Portfolio profissional desenvolvido **do zero**, sem uso de frameworks prontos como Laravel ou WordPress. Toda a arquitetura MVC foi construída manualmente, demonstrando domínio profundo sobre os fundamentos da linguagem PHP, padrões de projeto e boas práticas de engenharia de software.

O projeto vai muito além de uma simples página estática: trata-se de uma **aplicação web completa** com painel administrativo, sistema de blog com Markdown, formulário de contato com validação server-side, gerenciamento dinâmico de projetos, e uma infraestrutura robusta de segurança e performance.

**🌐 Acesse:** [viniciush.vnicidigital.com.br](https://viniciush.vnicidigital.com.br)

---

## 🖥️ Demonstração

| Página | Descrição |
|--------|-----------|
| **Home** | Hero section com animações, marquee de tecnologias, projetos em destaque e seção de expertise |
| **Projetos** | Listagem paginada de projetos com filtros por categoria, imagem e tecnologias usadas |
| **Blog** | Artigos renderizados a partir de Markdown com paginação e SEO otimizado |
| **Contato** | Formulário com validação em tempo real, honeypot anti-spam e rate limiting |
| **Admin** | Dashboard completo para gerenciamento de conteúdo (CRUD de projetos e posts) |

---

## 🏗️ Arquitetura e Estrutura

O projeto segue o padrão arquitetural **MVC (Model-View-Controller)** implementado manualmente, com separação clara de responsabilidades:

```
meu-portfolio/
├── index.php                # Front Controller — ponto de entrada único
├── .htaccess                # URL Rewriting, cache, segurança e compressão
├── Dockerfile               # Containerização com PHP 8.2 + Apache
├── docker-compose.yml       # Orquestração de containers
├── robots.txt               # Configuração de crawlers
│
├── config/
│   └── config.php           # Configurações globais, .env loader, helpers
│
├── core/                    # Framework MVC customizado
│   ├── App.php              # Router — resolve controller/method/params
│   ├── Autoloader.php       # PSR-4 style autoloading via spl_autoload
│   ├── Controller.php       # Base controller (CSRF, sanitização, JSON, redirect)
│   ├── Model.php            # Base model (validações reutilizáveis)
│   ├── View.php             # Template engine (layout system, components, escape)
│   └── Database.php         # Singleton PDO (SQLite + MySQL dual-driver)
│
├── app/
│   ├── controllers/         # Controllers da aplicação
│   │   ├── HomeController.php
│   │   ├── ProjectsController.php
│   │   ├── BlogController.php
│   │   ├── ContactController.php
│   │   ├── AboutController.php
│   │   └── ErrorController.php
│   ├── models/
│   │   └── ContactModel.php # Validações de formulário + envio de email
│   ├── views/
│   │   ├── layouts/         # Layout principal (main.php)
│   │   ├── pages/           # Views das páginas (home, blog, projects, etc.)
│   │   ├── components/      # Componentes reutilizáveis (navbar, footer)
│   │   └── emails/          # Templates HTML de email
│   ├── lib/
│   │   └── Parsedown.php    # Parser de Markdown para o blog
│   └── data/                # Banco de dados SQLite (dev)
│
├── admin/                   # Painel administrativo independente
│   ├── index.php            # Login com autenticação segura
│   ├── auth.php             # Guard de autenticação + helpers
│   ├── dashboard.php        # Dashboard com estatísticas
│   ├── projects/            # CRUD completo de projetos
│   ├── posts/               # CRUD completo de posts do blog
│   ├── partials/            # Sidebar e topbar reutilizáveis
│   └── assets/              # CSS/JS exclusivos do admin
│
└── public/
    ├── assets/
    │   ├── css/style.css    # Stylesheet principal (~45KB)
    │   └── js/main.js       # JavaScript principal (~13KB)
    ├── uploads/             # Uploads de imagens (projetos, blog, etc.)
    └── sitemap.php          # Gerador dinâmico de sitemap XML
```

### Fluxo de Requisição

```
Cliente → .htaccess (URL Rewrite) → index.php (Front Controller)
  → Autoloader → App.php (Router)
    → Controller → Model (dados) → View (renderização)
      → Layout + Components → Resposta HTML
```

---

## 🛠️ Tecnologias Utilizadas

### Back-end
| Tecnologia | Utilização |
|------------|------------|
| **PHP 8.2** | Linguagem principal, com tipagem forte e recursos modernos |
| **PDO** | Abstração de banco de dados com prepared statements |
| **SQLite** | Banco de dados em desenvolvimento (zero config) |
| **MySQL** | Banco de dados em produção (Hostinger) |
| **Parsedown** | Parser Markdown → HTML para o sistema de blog |
| **Apache** | Servidor web com mod_rewrite para URLs amigáveis |

### Front-end
| Tecnologia | Utilização |
|------------|------------|
| **HTML5 Semântico** | Estrutura acessível com tags semânticas (`<header>`, `<main>`, `<section>`, `<article>`) |
| **CSS3 Vanilla** | Estilização completa sem frameworks — design system proprietário |
| **JavaScript ES6+** | Interatividade, animações, cursor customizado, scroll reveal |
| **Google Fonts** | Tipografia profissional (Inter + Space Grotesk) |

### DevOps & Infraestrutura
| Tecnologia | Utilização |
|------------|------------|
| **Docker** | Containerização com PHP 8.2-Apache para ambiente consistente |
| **Docker Compose** | Orquestração simplificada com hot-reload via volumes |
| **Git** | Versionamento com `.gitignore` profissional |
| **Hostinger** | Hospedagem em produção com MySQL |

---

## ⚡ Funcionalidades

### 🏠 Página Inicial
- **Hero section** com animações de reveal e tipografia impactante
- **Marquee infinito** exibindo stack de tecnologias
- **Projetos em destaque** com preview de imagem no hover
- **Seção de expertise** com skill cards organizados por área
- **Cursor customizado** com efeito de seguimento suave
- **Preloader animado** com barra de progresso

### 📁 Projetos
- Listagem dinâmica com dados do banco de dados
- **Paginação server-side** configurável
- Página individual de cada projeto com galeria e detalhes técnicos
- Filtro por status (published/draft) automático
- **URLs amigáveis** via slugs (`/projects/show/nome-do-projeto`)

### 📝 Blog
- Sistema completo de blog com **renderização Markdown**
- Paginação de posts com navegação intuitiva
- Cada post com meta description dinâmica para SEO
- **Cache de queries** para evitar consultas redundantes (N+1)
- Suporte a tabelas, código, imagens e formatação rica

### 📬 Formulário de Contato
- Validação completa no **back-end** (nome, email, telefone brasileiro, mensagem)
- **Honeypot field** para proteção contra bots
- **Rate limiting** por IP (1 envio por minuto)
- Envio de email com template HTML profissional
- **Log de submissões** para auditoria
- Respostas via **JSON API** para UX sem reload

### 🔐 Painel Administrativo
- **Dashboard** com estatísticas de projetos e posts
- **CRUD completo** de projetos (criar, editar, excluir)
- **CRUD completo** de posts do blog
- Editor com **preview Markdown em tempo real**
- Upload de imagens para projetos
- Sistema de status (published/draft)
- Interface dark theme com design profissional

---

## ✅ Boas Práticas Aplicadas

### Arquitetura e Design Patterns
- **MVC Pattern** — Separação total entre lógica de negócio, apresentação e dados
- **Front Controller** — Ponto de entrada único (`index.php`) para todas as requisições
- **Singleton Pattern** — Classe `Database` garante uma única conexão PDO
- **Template Engine** — Sistema de layouts com `ob_start()/ob_get_clean()` para composição de views
- **Component System** — Componentes reutilizáveis (navbar, footer) via `View::component()`
- **Autoloading** — `spl_autoload_register` para carregamento automático de classes

### Qualidade de Código
- **DRY (Don't Repeat Yourself)** — Classe base `Controller` e `Model` com métodos reutilizáveis
- **Sanitização centralizada** — `htmlspecialchars()` com `ENT_QUOTES` e `UTF-8` em toda entrada
- **Helper functions globais** — `url()`, `asset()`, `uploads()`, `clean()`, `dd()` para produtividade
- **Nomenclatura consistente** — Controllers com sufixo `Controller`, views com nomes descritivos
- **Comentários e documentação** — Código comentado em português para facilitar manutenção

### Banco de Dados
- **Prepared Statements** — Todas as queries usam PDO com bind de parâmetros, prevenindo SQL Injection
- **Dual-driver** — Suporte transparente a SQLite (dev) e MySQL (prod) via configuração
- **Error Mode Exception** — `PDO::ERRMODE_EXCEPTION` para captura adequada de erros
- **Fetch Associativo** — `PDO::FETCH_ASSOC` como modo padrão para arrays limpos
- **Cache de resultados** — Propriedade estática `$cachedPosts`/`$cachedProjects` para evitar N+1

### Configuração e Ambiente
- **Variáveis de ambiente** — Arquivo `.env` com loader customizado, nunca versionado no Git
- **Configuração por ambiente** — `ENVIRONMENT` controla exibição de erros e comportamento
- **Constantes organizadas** — Paths, URLs, e configurações definidas centralmente em `config.php`
- **`.gitignore` profissional** — Protege `.env`, SQLite, uploads, logs e dependências

---

## 🔒 Segurança

| Proteção | Implementação |
|----------|---------------|
| **CSRF Tokens** | Gerados com `bin2hex(random_bytes(32))`, validados com `hash_equals()` |
| **Autenticação Argon2ID** | Hash de senha com `PASSWORD_ARGON2ID` + rehash automático |
| **XSS Prevention** | Output escaping com `htmlspecialchars(ENT_QUOTES, UTF-8)` em toda renderização |
| **SQL Injection** | Prepared statements com PDO em 100% das queries |
| **Honeypot Anti-Spam** | Campo invisível no formulário de contato para detecção de bots |
| **Rate Limiting** | Limite de 1 submissão/minuto por IP via sessão |
| **Security Headers** | `X-Frame-Options`, `X-Content-Type-Options`, `X-XSS-Protection`, `Referrer-Policy`, `Permissions-Policy` |
| **Directory Traversal** | `Options -Indexes`, bloqueio de acesso a `/app`, `/core`, `/config` via `.htaccess` |
| **Dot-file Protection** | Bloqueio de arquivos iniciados com `.` (`.env`, `.git`, etc.) |
| **Session Security** | `cookie_httponly`, `use_only_cookies`, `cookie_secure` habilitados |
| **Server Fingerprint** | Headers `Server` e `X-Powered-By` removidos |
| **Sensitive Files** | Arquivos `.ini`, `.log` e `.md` bloqueados via `FilesMatch` |
| **Admin Isolation** | Painel admin excluído do router MVC, com guard de autenticação próprio |

---

## 🚀 SEO e Performance

### SEO
- **Meta tags dinâmicas** — `<title>` e `<meta description>` por página via controllers
- **Open Graph & Twitter Cards** — Compartilhamento otimizado em redes sociais
- **Schema.org (JSON-LD)** — Structured data para Rich Snippets no Google
- **Canonical URLs** — Prevenção de conteúdo duplicado
- **Sitemap XML dinâmico** — Gerado automaticamente a partir do banco de dados
- **robots.txt** — Controle de indexação com bloqueio de áreas sensíveis
- **URLs semânticas** — Slugs amigáveis em vez de IDs numéricos
- **HTML5 semântico** — Uso correto de `<header>`, `<main>`, `<section>`, `<article>`, `<nav>`

### Performance
- **Compressão GZIP** — `mod_deflate` para HTML, CSS, JS, JSON, SVG e fontes
- **Cache de navegador** — `mod_expires` com cache de 1 ano para assets estáticos
- **Font Preconnect** — `<link rel="preconnect">` para Google Fonts
- **Imagens WebP/AVIF** — Formatos modernos para otimização de tamanho
- **CSS/JS otimizados** — Arquivo único de CSS e JS, sem dependências externas pesadas
- **Query Caching** — Cache em memória para evitar consultas repetidas ao banco

---

## 🖥️ Painel Administrativo

O painel admin é uma aplicação independente dentro do projeto, com:

- **Autenticação segura** com Argon2ID e rehash automático
- **Dashboard interativo** com contadores de projetos/posts
- **CRUD de Projetos** — Criar, editar e excluir com upload de imagem
- **CRUD de Blog** — Editor com suporte a Markdown e preview
- **Proteção CSRF** em todas as operações de escrita
- **Design dark-theme** profissional com a identidade visual do portfólio
- **Responsivo** — Funciona em desktop e dispositivos móveis
- Indexação bloqueada via `<meta name="robots" content="noindex, nofollow">`

---

## 🐳 Como Executar

### Com Docker (Recomendado)

```bash
# Clone o repositório
git clone https://github.com/seu-usuario/meu-portfolio.git
cd meu-portfolio

# Copie o arquivo de ambiente
cp .env.example .env

# Configure as variáveis de ambiente
nano .env

# Suba os containers
docker-compose up -d

# Acesse em http://localhost:8080
```

### Sem Docker

**Requisitos:**
- PHP 8.2+ com extensões `pdo`, `pdo_sqlite` (ou `pdo_mysql`), `mbstring`
- Apache com `mod_rewrite` habilitado
- Composer (opcional)

```bash
# Clone e configure
git clone https://github.com/seu-usuario/meu-portfolio.git
cd meu-portfolio
cp .env.example .env

# Aponte o DocumentRoot do Apache para a raiz do projeto
# Ou use o servidor embutido do PHP:
php -S localhost:8000
```

---

## 🔐 Variáveis de Ambiente

| Variável | Descrição | Exemplo |
|----------|-----------|---------|
| `EMAIL_TO` | Email para receber mensagens de contato | `seu@email.com` |
| `EMAIL_FROM` | Email remetente | `noreply@seusite.com` |
| `SECRET_KEY` | Chave secreta para tokens CSRF | `sua-chave-aleatoria` |
| `BASE_URL` | URL base do site | `https://seusite.com` |
| `ENVIRONMENT` | Ambiente de execução | `development` ou `production` |
| `DB_CONNECTION` | Driver do banco de dados | `sqlite` ou `mysql` |
| `DB_HOST` | Host do banco (MySQL) | `127.0.0.1` |
| `DB_PORT` | Porta do banco (MySQL) | `3306` |
| `DB_DATABASE` | Nome/caminho do banco | `app/data/database.sqlite` |
| `DB_USERNAME` | Usuário do banco (MySQL) | `root` |
| `DB_PASSWORD` | Senha do banco (MySQL) | ` ` |

---

## 📫 Contato

<p align="center">
  <strong>Vinicius Henrique</strong><br/>
  Desenvolvedor Full Stack
</p>

<p align="center">
  <a href="https://viniciush.vnicidigital.com.br">🌐 Portfolio</a> •
  <a href="https://viniciush.vnicidigital.com.br/contact">📬 Contato</a>
</p>

---

<p align="center">
  <sub>Desenvolvido com 💜 por <strong>Vinicius Henrique</strong></sub>
</p>
