CREATE TABLE flood (
    id      varchar(26) not null primary key,
    ip      varchar(15) not null,
    first   int(10) not null,
    last    int(10) not null,
    visit   int(3) not null
);