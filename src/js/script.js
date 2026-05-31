class Disclosure {
	constructor(el) {
		this.el = el;
		this.btns = this.el.querySelectorAll('.disclosure-btn');
		this.panel = this.el.querySelector('.disclosure-panel');
		this.isOpen = false;

		this.handleClickOutside = this.handleClickOutside.bind(this);

		this.init();

	}

	init() {
		if (!this.el || !this.btns || !this.panel) return;
		this.btns.forEach((btn) => {
			btn.addEventListener('click', () => this.toggle());
		})
	}

	toggle() {
		this.isOpen ? this.hidden() : this.show();
	}

	handleClickOutside(e) {
		if (!this.el.contains(e.target)) {
			this.hidden();
		}
	}

	show() {
		this.isOpen = true;
		this.panel.classList.remove('opacity-0');
		this.panel.classList.remove('pointer-events-none');
		this.panel.classList.add('opacity-100');
		this.panel.classList.add('pointer-events-auto')

		document.addEventListener('click', this.handleClickOutside);
	}

	hidden() {
		this.isOpen = false;
		this.panel.classList.remove('opacity-100');
		this.panel.classList.remove('pointer-events-auto');
		this.panel.classList.add('opacity-0');
		this.panel.classList.add('pointer-events-none')

		document.removeEventListener('click', this.handleClickOutside);
	}
}

document.querySelectorAll('.disclosure').forEach((el) => {
	new Disclosure(el);
})

class Modal {
  constructor(el) {
    this.el = el;
    this.btn = this.el.querySelector(".modal-btn");
    this.btnClose = this.el.querySelector(".modal-close")
    this.panel = this.el.querySelector(".modal-panel");
    this.isOpen = false;

    this.handleClickOutside = this.handleClickOutside.bind(this);

    this.init();
  }

  init() {
    if (!this.el || !this.btn || !this.panel || !this.btnClose) return;
    this.btn.addEventListener("click", () => this.toggle());
    this.btnClose.addEventListener("click", () => this.hidden())
  }

  toggle() {
    this.isOpen ? this.hidden() : this.show();
  }

  handleClickOutside(e) {
    if (!this.el.contains(e.target)) {
      this.hidden();
    }
  }

  show() {
    this.isOpen = true;
    this.panel.classList.remove(
      "transition-all",
      "duration-300",
      "opacity-0",
      "pointer-events-none"
    );
    this.panel.classList.add(
      "transition-all",
      "duration-300",
      "opacity-100",
      "pointer-events-auto"
    );

    document.addEventListener("click", this.handleClickOutside);
  }

  hidden() {
    this.isOpen = false;
    this.panel.classList.remove(
      "transition-all",
      "duration-300",
      "opacity-100",
      "pointer-events-auto"
    );
    this.panel.classList.add(
      "transition-all",
      "duration-300",
      "opacity-0",
      "pointer-events-none"
    );

    document.removeEventListener("click", this.handleClickOutside);
  }
}

document.querySelectorAll(".modal").forEach((el) => {
  new Modal(el);
});

class PreviewImage {
	constructor(input, preview, placeholder) {
		this.input = document.getElementById(input);
		this.preview = document.getElementById(preview);
		this.placeholderText = document.getElementById(placeholder)

		this.init();
	}

	init() {
		if (!this.input || !this.preview || !this.placeholderText) return;
		this.input.addEventListener('change', (e) => this.handlePreview(e))
	}

	handlePreview(event) {
		const file = event.target.files[0]
		if (!file) return;

		const url = URL.createObjectURL(file);
		this.preview.src = url
		this.preview.classList.remove('hidden')
		this.placeholderText.classList.add('hidden')
	}
}

new PreviewImage('previewInput', 'previewImg', 'previewPlaceholder')

class ShowPassword {
	constructor(el) {
		this.container = el;
		this.input = this.container.querySelector('.password-input')
		this.btnIcon = this.container.querySelector('.password-btn-icon');

		this.showIcon = 'bx-eye';
		this.hideIcon = 'bx-eye-slash';

		this.handleToggle = this.handleToggle.bind(this);

		this.init();
	}

	init() {
		if (!this.container || !this.input || !this.btnIcon) return;
		this.btnIcon.addEventListener('click', this.handleToggle);
	}

	handleToggle() {
		const isPassword = this.input.type === 'password';

		this.input.type = isPassword ? 'text' : 'password';

		this.renderIcon(isPassword);
	}

	renderIcon(isPassword) {
		this.btnIcon.classList.remove(this.showIcon, this.hideIcon);
		this.btnIcon.classList.add(isPassword ? this.hideIcon : this.showIcon);
	}
}

document.querySelectorAll('.show-password').forEach((el) => {
	new ShowPassword(el);
})

class Sidebar {
  constructor() {
    this.btn = document.getElementById("sidebarBtn");
    this.panel = document.getElementById("sidebar");

    this.isOpen = false;

    this.init();
  }

  init() {
    if (!this.btn || !this.panel) return;

    this.btn.addEventListener("click", () => this.toggle());
  }

  toggle() {
    this.isOpen ? this.hidden() : this.show();
  }

  show() {
    this.isOpen = true;
    this.panel.classList.remove(
      "max-md:opacity-0",
      "max-md:pointer-events-none",
      "max-md:-left-full"
    );
    this.panel.classList.add(
      "max-md:opacity-100",
      "max-md:pointer-events-auto",
      "max-md:left-0"
    );

    document.addEventListener("click", this.handleClickOutside);
  }

  hidden() {
    this.isOpen = false;
    this.panel.classList.remove(
      "max-md:opacity-100",
      "max-md:pointer-events-auto",
      "max-md:left-0"
    );
    this.panel.classList.add(
      "max-md:opacity-0",
      "max-md:pointer-events-none",
      "max-md:-left-full"
    );

    document.removeEventListener("click", this.handleClickOutside);
  }
}

new Sidebar();

class LoadingBtn {
    constructor(form) {
        this.form = form;
        this.btn = this.form.querySelector('.form-btn');
        this.text = this.btn.querySelector('.form-btntext');

        this.init();
    }

    init() {
        if (!this.form || !this.btn || !this.text) return;
        this.form.addEventListener('submit', () => this.startLoad())
    }

    startLoad() {
        this.btn.disabled = true;
        this.btn.classList.remove('cursor-pointer')
        this.btn.classList.add('opacity-60')
        this.text.innerText = 'Loading...';
    }
}

document.querySelectorAll('.form').forEach((el) => {
	new LoadingBtn(el);
});

document.addEventListener('DOMContentLoaded', () => {
    const success = document.getElementById('toast-success');
    const error = document.getElementById('toast-error');

    [success, error].forEach(toast => {
        if (!toast) return;

        setTimeout(() => {
            toast.classList.add(
                'opacity-0',
                'translate-x-10'
            );

            setTimeout(() => toast.remove(), 300);
        }, 3000);
    });
});