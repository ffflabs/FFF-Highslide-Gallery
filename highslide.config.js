
hs.outlinesDir = '/';
hs.transitions= ['expand', 'crossfade'];
hs.showCredits = false;
hs.outlineType = 'custom';
hs.dimmingOpacity = 0.7;
hs.align = 'center';
hs.allowMultipleInstances = false;
hs.captionEval = 'this.thumb.alt';
hs.captionOverlay.position = 'above';
hs.numberPosition = 'heading';

// Add the slideshow controller


hs.addSlideshow({

	interval: 5000,
	repeat: true,
	useControls: true,
	fixedControls: 'fit',
	overlayOptions: {
		opacity: 0.75,
		position: 'top right',
		offsetX: 7,
		offsetY: -58,
		hideOnMouseOut: false
	}
});


// Spanish language strings
hs.lang = {
	cssDirection: 'ltr',
	loadingText: 'Cargando...',
	loadingTitle: 'Click para cancelar',
	focusTitle: 'Click para traer al frente',
	fullExpandTitle: 'Expandir al tama�o actual',
	creditsText: 'Potenciado por <i>Highslide JS</i>',
	creditsTitle: 'Ir al home de Highslide JS',
	previousText: 'Anterior',
	nextText: 'Siguiente',
	moveText: 'Mover',
	closeText: 'Cerrar',
	closeTitle: 'Cerrar (esc)',
	resizeTitle: 'Redimensionar',
	playText: 'Iniciar',
	playTitle: 'Iniciar slideshow (barra espacio)',
	pauseText: 'Pausar',
	pauseTitle: 'Pausar slideshow (barra espacio)',
	previousTitle: 'Anterior (flecha izquierda)',
	nextTitle: 'Siguiente (flecha derecha)',
	moveTitle: 'Mover',
	fullExpandText: 'Tama�o real',
	number: 'Imagen %1 de %2',
	restoreTitle: 'Click para cerrar la imagen, click y arrastrar para mover. Usa las flechas del teclado para avanzar o retroceder.'
};



var iframegroup = {objectType: 'iframe', height:1000, width:1600,preserveContent : false,wrapperClassName: 'no-controls'}


hs.Expander.prototype.onAfterExpand = function() {
_gaq.push(['_trackPageview', this.a.href]);

}

jQuery('.no-controls div div div div.highslide-controls  .highslide-full-expand').click(function() {location.href="http://www.chw.net"; });
