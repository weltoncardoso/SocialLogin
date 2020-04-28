create table users
(
    id int auto_increment,
    first_name varchar(255) not null,
    last_name varchar(255) not null,
    email varchar(255) not null,
    passwd varchar(255) not null,
    facebook_id varchar(30) null,
    google_id varchar(30) null,
    photo varchar(255) null,
    forget varchar(255) null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint users_pk
        primary key (id)
);