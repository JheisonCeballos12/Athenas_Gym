document.addEventListener("DOMContentLoaded", () => {
  const frases = [
    "¡Nunca te rindas!",
    "Hoy es un buen día para entrenar.",
    "Tu cuerpo puede soportarlo, ¡es tu mente la que tienes que convencer!",
    "Cada día es una nueva oportunidad para mejorar.",
    "El dolor de hoy es la fuerza del mañana.",
    "No cuentes los días, haz que los días cuenten.",
    "Hazlo por ti. Hazlo por tu salud.",
    "La disciplina supera la motivación.",
    "Entrena con propósito, no con excusas.",
    "El éxito no es para los que piensan en hacerlo, ¡es para los que lo hacen!"
  ];

  let index = 0;
  const textElement = document.getElementById("motivational-text");

  if (textElement) {
    setInterval(() => {
      index = (index + 1) % frases.length;
      textElement.textContent = frases[index];
    }, 4000);
  }
});
