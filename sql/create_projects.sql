-- PARA CRIAÇÃO DO BANCO DE DADOS EM SQLITE3

CREATE TABLE IF NOT EXISTS projects (

    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    slug            TEXT NOT NULL UNIQUE
    title           TEXT NOT NULL,
    date            TEXT NOT NULL,
    date_iso        TEXT NOT NULL,
    category        TEXT NOT NULL,
    excerpt         TEXT NOT NULL,
    content         TEXT NOT NULL,
    reading_time    INTEGER,
    status          TEXT DEFAULT 'draft',
    created_at      TEXT DEFAULT CURRENT_TIMESTAMP

);