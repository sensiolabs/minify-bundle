function greet(e){return"Hello, "+e+"!"}const greetArrow=e=>`Hello, ${e}!`;console.log(greet(name)),console.log(greetArrow(name)),console.log(user.profile?.username??"Guest"),console.log(user.address?.street??"No address provided")