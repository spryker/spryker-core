import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { SpinnerSize } from '@spryker/spinner';

@Component({
    selector: 'mp-url-html-renderer',
    templateUrl: './url-html-renderer.component.html',
    styleUrls: ['./url-html-renderer.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-url-html-renderer',
    },
})
export class UrlHtmlRendererComponent {
    @Input() url = '';
    @Input() method = '';
    @Input() spinnerSize = SpinnerSize.Default;
}
