# Test FDJ

## Requirements

Cette application nécessite d'avoir installer `docker` et `docker-compose`.

## Installation

``make install`` 

## Url partie obligatoire

[http://localhost/euro-millions/results](http://localhost/euro-millions/results)

*NOTE*  
*Le poste étant exclusivement pour du développement backend, je ne me suis pas attardé sur l'IHM*

## Partie optionnelle

Une série de test unitaires et fonctionnelles ont été réalisé, et peuvent être executé de cette manière:  
``make test-all`` (pour toute la suite)  
``make test-unit`` (pour les tests unitaires seulement)  
``make test-functional`` (pour les tests fonctionnelles seulement)

## Pour aller plus loin

Il est possible d'améliorer significativement les performances de cette application.  
Plusieurs choses auraient pu être mis en place, mais que je n'ai pas fait car trop long et laborieux pour un test technique.  
En revanche c'est avec plaisir que nous pourrons parler plus en détails des points que je vais évoquer.  

#### Optimisation
* J'ai créer un normalizer pour filtrer au maximum les données inutile pour l'IHM, cependant c'est un moyens dérisoire d'améliorer les performances.  
* La meilleurs solution aurait été de mettre un **reverse proxy** coupler à un **CDN**, et de cacher la page (plus de 24h si les numéro ne sont pas amené à changer souvent)  
* Egalement un **varnish** est la bienvenue devant l'API  
* Enfin le tout devra être couplé à une stratégie de décache de bout en bout:  
  1. L'API lorsqu'elle enregistre les nouveaux numéro envoie une requête **PURGE** au Varnish
  2. L'API envoie un message de décache dans une queue (**rabbitMQ** ou autre) consommer par ses clients (cette application entre autre)
  3. Les clients envoient l'ordre de décache aux CDN  
  4. Il est même possible de mettre en place un pré chauffage du nouveau rendu aussitôt celui-ci décaché  

#### Stratégie de test
* Pour une stratégie de test complète, on aurai pu mettre en place des **tests de contrat** avec l'API afin de s'assurer qu'une mise à jour de l'API ne casse pas les contrats
* Egalement des **tests End to End** serait la bienvenue (avec **Cypress**, ou encore avec **postman** pour les routes d'API, couplé à **newman** pour la CI)

#### Intégration continue
J'ai prit le partie de ne pas faire une CI, mais il faudrait biensur que toute ces suites de tests s'exécute de manière automatique à chaque commit.
