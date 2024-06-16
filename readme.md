# Infonet Technical Test

## Requirements

A Star Wars character app which imports data from the swapi API and allows for the editing and deleting of characters.

## Data Importing

Using [the swapi API](https://swapi.dev/) download 30 characters and all movies

https://swapi.dev/api/people
https://swapi.dev/api/films

## Routes

GET / - list characters (with search and delete functionality)
GET /character/{characterId}/edit - edit character
POST /character/{characterId}/delete - delete character
GET /movies - list movies
GET /movies/{movieId} - show movie (with characters)

## Database Structure

characters
id
name
mass
height
gender
picture (custom)
movies
id
name
movies_characters
movie_id
character_id
