gsap.registerPlugin(ScrollTrigger);

/* Camera parallax on scroll */
gsap.to(".hero-visual",{
  rotateY:20,
  rotateX:-10,
  scrollTrigger:{
    trigger:".hero",
    start:"top top",
    end:"bottom top",
    scrub:true
  }
});

/* Text fade on scroll */
gsap.from(".hero-text",{
  y:80,
  opacity:0,
  duration:1.5
});

/* Gallery reveal */
gsap.from(".gallery h2",{
  opacity:0,
  y:60,
  scrollTrigger:{
    trigger:".gallery",
    start:"top 80%"
  }
});

/* Mouse 3D tilt */
document.addEventListener("mousemove",e=>{
  let x=(e.clientX/window.innerWidth-.5)*20;
  let y=(e.clientY/window.innerHeight-.5)*20;

  gsap.to(".hero-visual",{
    rotateY:x,
    rotateX:-y,
    duration:.6
  });
});
