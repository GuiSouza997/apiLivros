/** Criando Base de Dados da Livraria */
create database livraria;

/** Criando tabela de 'livros'  com id auto_increment (gerado automaticamente pelo banco através de indice) */
CREATE TABLE public.livros (
    id serial4 NOT NULL,
    usuario_publicador_id integer not null,
    titulo varchar NOT NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    delete_at timestamp NULL,
    CONSTRAINT livros_pkey PRIMARY KEY (id)
);

CREATE INDEX livros_id_idx ON public.livros USING btree (id);

/** Criando tabela de indices com chave primaria e estrangeira (vinculando à tabela livros (livro_id))*/
CREATE TABLE public.indices (
    id serial4 NOT NULL,
    livro_id integer not null,
    indice_pai_id integer not null,
    titulo varchar NOT NULL,
    pagina integer not null,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    delete_at timestamp NULL,
    CONSTRAINT indices_pkey PRIMARY KEY (id)
);

CREATE INDEX indices_id_idx ON public.indices USING btree (id);

ALTER TABLE
    public.indices
ADD
    CONSTRAINT indices_livro_id_fkey FOREIGN KEY (livro_id) REFERENCES public.livros (id);