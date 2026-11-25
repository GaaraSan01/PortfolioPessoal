-- PARA CRIAÇÃO DO BANCO DE DADOS EM SQLITE3

CREATE TABLE IF NOT EXISTS projects (

    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    slug                TEXT NOT NULL UNIQUE,
    title               TEXT NOT NULL,
    category            TEXT,
    year                TEXT,
    image               TEXT,
    description         TEXT,
    long_description    TEXT,
    technologies        TEXT,
    url                 TEXT,
    status              TEXT DEFAULT 'draft',
    created_at          TEXT DEFAULT CURRENT_TIMESTAMP

);
