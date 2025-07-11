import { HttpClient } from '@angular/common/http';
import {
    AfterContentInit,
    ChangeDetectionStrategy,
    Component,
    Injector,
    Input,
    OnChanges,
    OnDestroy,
    OnInit,
    SimpleChanges,
    ViewEncapsulation,
} from '@angular/core';
import { AjaxActionService } from '@spryker/ajax-action';
import { Observable, of, Subject } from 'rxjs';
import { catchError, switchMap, takeUntil } from 'rxjs/operators';

interface SubmitEvent extends Event {
    submitter: HTMLElement | null;
}

interface FormSubmission {
    form: HTMLFormElement;
    event?: SubmitEvent;
    isAjax: boolean;
}

@Component({
    selector: 'mp-mfa-handler',
    templateUrl: './mfa-handler.component.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class MfaHandlerComponent implements OnChanges, OnInit, AfterContentInit, OnDestroy {
    @Input() formSelector: string;
    @Input() url: string;
    @Input() ajaxFormSelector: string;
    private formSubmission$ = new Subject<FormSubmission>();
    private destroyed$ = new Subject<void>();

    private formSubmitHandler: ((event: Event) => void) | null = null;
    private ajaxFormSubmitHandler: ((event: Event) => void) | null = null;

    constructor(
        private httpClient: HttpClient,
        private ajaxActionService: AjaxActionService,
        private injector: Injector,
    ) {}

    ngOnInit(): void {
        this.formSubmission$
            .pipe(
                switchMap((submission) => this.processFormSubmission(submission)),
                catchError((response) => of(response)),
                takeUntil(this.destroyed$),
            )
            .subscribe((response) => {
                this.ajaxActionService.handle(response, this.injector);
            });
    }

    ngOnChanges(changes: SimpleChanges): void {
        if (
            this.formSelector &&
            this.url &&
            changes.formSelector?.previousValue !== changes.formSelector?.currentValue
        ) {
            this.setupFormHandler();
        }

        if (
            this.ajaxFormSelector &&
            this.url &&
            changes.ajaxFormSelector?.previousValue !== changes.ajaxFormSelector?.currentValue
        ) {
            this.setupAjaxFormHandler();
        }
    }

    ngAfterContentInit(): void {
        if (this.formSelector && this.url) {
            this.setupFormHandler();
        }

        if (this.ajaxFormSelector && this.url) {
            this.setupAjaxFormHandler();
        }
    }

    ngOnDestroy(): void {
        this.destroyed$.next();
        this.destroyed$.complete();
    }

    private setupFormHandler(): void {
        const formElement = document.querySelector(this.formSelector) as HTMLFormElement;
        if (!formElement) {
            console.error(`MFA Handler: No element found with selector "${this.formSelector}"`);
            return;
        }

        if (this.formSubmitHandler) {
            formElement.removeEventListener('submit', this.formSubmitHandler);
        }

        this.formSubmitHandler = (event: Event) => {
            const submitEvent = event as SubmitEvent;
            if (
                formElement.hasAttribute('data-locked') ||
                (submitEvent.submitter && submitEvent.submitter.hasAttribute('data-locked'))
            ) {
                return;
            }

            event.preventDefault();
            this.sendFormData(formElement);
        };

        formElement.addEventListener('submit', this.formSubmitHandler);
    }

    private setupAjaxFormHandler(): void {
        const ajaxFormElement = document.querySelector(this.ajaxFormSelector) as HTMLElement;
        if (!ajaxFormElement) {
            console.error(`MFA Handler: No ajax form element found with selector "${this.ajaxFormSelector}"`);
            return;
        }

        const formElement = ajaxFormElement.querySelector('form') as HTMLFormElement;
        if (!formElement) {
            console.error(`MFA Handler: No form element found within ajax form "${this.ajaxFormSelector}"`);
            return;
        }

        if (this.ajaxFormSubmitHandler) {
            formElement.removeEventListener('submit', this.ajaxFormSubmitHandler, true);
        }

        this.ajaxFormSubmitHandler = (event: Event) => {
            const submitEvent = event as SubmitEvent;
            if (
                formElement.hasAttribute('data-locked') ||
                (submitEvent.submitter && submitEvent.submitter.hasAttribute('data-locked'))
            ) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            this.sendAjaxFormData(formElement, submitEvent);
        };

        formElement.addEventListener('submit', this.ajaxFormSubmitHandler, true);
    }

    private sendFormData(form: HTMLFormElement): void {
        this.formSubmission$.next({ form, isAjax: false });
    }

    private sendAjaxFormData(form: HTMLFormElement, event: SubmitEvent): void {
        this.formSubmission$.next({ form, event, isAjax: true });
    }

    private processFormSubmission(submission: FormSubmission): Observable<unknown> {
        const { form, event, isAjax } = submission;
        const formData = new FormData(form);

        if (!isAjax || !event) {
            formData.append('form_selector', this.formSelector);
            return this.makeRequest(formData);
        }

        const submitElement = event.submitter;
        if (submitElement) {
            const submitName = submitElement.getAttribute('name');
            const submitValue = submitElement.getAttribute('value') ?? '';

            if (submitName) {
                formData.append(submitName, submitValue);
            }
        }

        formData.append('ajax_form_selector', this.ajaxFormSelector);
        return this.makeRequest(formData);
    }

    private makeRequest(formData: FormData): Observable<unknown> {
        return this.httpClient.post<unknown>(this.url, formData);
    }
}
