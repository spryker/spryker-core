import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-url-html-renderer',
    templateUrl: './url-html-renderer.component.html',
    styleUrls: ['./url-html-renderer.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class UrlHtmlRendererComponent {
    @Input() url = '';
    @Input() method = '';
}
