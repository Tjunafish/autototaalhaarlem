window.addEvent('domready', function (e) {
	/* custom select box */
	var SelectBox = new Class({

	Implements: Events,

	initialize: function(el, klass){
		this.el     = el;
		this.klass  = klass || 'sb';
		this.isOpen = false;
		this.index  = false; 
		this.generateHTML();
		this.attach();
	},

	reinit: function(){
		this.fx = false;
		this.isOpen = false;
		this.regenerateHTML();
		this.attach();
	},

	regenerateHTML: function(){
		this.container.destroy();
		this.generateHTML();
	},

	generateHTML: function(){
		var self       = this;
		var options    = this.el.getElements('option');
		var selected  = this.el.getSelected() || options[0];
		this.container = new Element('div.container.'+this.klass);
		this.arrow      = new Element('div.arrow.'+this.klass);

		this.container.adopt(this.arrow);
		this.display   = new Element('div.display.'+this.klass, {
			html: selected.get('html'),
			rel: options.indexOf(selected)
		});
		this.container.adopt(this.display);

		this.list = new Element('div.list.'+this.klass);
		var item;
		Array.each(options, function(el, i){
			item = new Element('span.option.'+this.klass, {
				html: el.get('html'),
				rel: i
			});
			self.list.adopt(item);
		});
		this.container.adopt(this.list);
		this.container.inject(this.el, 'after');
	},

	attach: function(){
		var self = this;
		this.fx  = new Fx.Slide(this.list, {
			duration: 150,
			transition: Fx.Transitions.Pow.easeOut
		});
		this.fx.hide();
		this.container.addEvent('click:relay(span.option)', function(){
			self.close();
			self.setValue(this.get('html'), this.get('rel'));
		});
		this.display.addEvent('click', function(){
			if(self.isOpen) self.close();
			else self.open();
		});
		this.arrow.addEvent('click', function(){
			if(self.isOpen) self.close();
			else self.open();
		});
	},

	open: function(){
		if(this.isOpen) return;
		this.isOpen = true;
		this.fx.slideIn();

	},

	close: function(){;
		if(!this.isOpen) return;
		this.isOpen = false;
		this.fx.slideOut();
	},

	setValue: function(html, i){
		this.el.selectedIndex = i;
		var element = this.el;
		if ("createEvent" in document) {
			var evt = document.createEvent("HTMLEvents");
			evt.initEvent("change", false, true);
			element.dispatchEvent(evt);
		} else {
			element.fireEvent("onchange");
		}
		this.fireEvent('change', { value: this.el.getSelected()[0].get('value')});
		this.display.set('html', html);
	},

	getValue: function(){
		return this.el.getSelected()[0].get('value');
	}
});
	/* simple fade */
	var SimpleFader=new Class({initialize:function(e){this.slids=e;this.idx=0;this.activeSlid=e[this.idx];e.morph({opacity:0});e[this.idx].morph({opacity:1});this.attach()},attach:function(){var e=this;var t=setInterval(function(){e.nextSlid(e)},1e4)},fadeTo:function(e){if(this.idx!==e){this.activeSlid.morph({opacity:0});this.activeSlid=this.slids[e];this.activeSlid.morph({opacity:1});this.idx=e}},nextSlid:function(e){var t=e.idx+1;if(t>=e.slids.length)t=0;e.fadeTo(t)}})

	/* code */
	var faders = [];
	Array.each($$('.fader'), function(el) {
		faders.push(new SimpleFader( el.getElements('div') ) );
	});

	var selects = [];
	Array.each($$('.sb'), function(el) {
		selects.push(new SelectBox( el ) );
	});

	$(document.body).addEvent('click',function(e) {
		Array.each(selects, function(self){
			if(!e.target || !$(e.target).getParents().contains(self.container)) { 
				//hide the menu! clicked outside!
				self.fx.slideOut();
				self.isOpen = false;
			}
		});
	});
});