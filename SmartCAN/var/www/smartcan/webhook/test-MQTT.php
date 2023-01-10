// curl -X POST -d '{"target":"TrafficLight-Green","cmd":"{\"state\": \"TOGGLE\",\"brightness\":127,\"color\":{\"rgb\":\"27,131,39\"}}"}'  -H "Content-type: application/json" http://localhost:1880/toMQTT
//
// curl -X POST -d '{"target":"TrafficLight-Green","cmd":"{\"state\": \"TEMPO\",\"brightness\":127,\"color\":{\"rgb\":\"27,131,39\"}}"}'  -H "Content-type: application/json" http://localhost:1880/toMQTT
//
// curl -X POST -d '{"target":"TrafficLight-Green","cmd":"{\"state\": \"ON\",\"brightness\":127,\"color\":{\"rgb\":\"27,131,39\"}}"}'  -H "Content-type: application/json" http://localhost:1880/toMQTT
//
// curl -X POST -d '{"target":"TrafficLight-Green","cmd":"{\"state\": \"OFF\"}"}'  -H "Content-type: application/json" http://localhost:1880/toMQTT