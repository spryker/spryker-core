import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation, OnChanges } from '@angular/core';
import { ToJson } from '@spryker/utils';

export interface Image {
    src: string;
    alt?: string;
}

@Component({
    selector: 'mp-image-slider',
    templateUrl: './image-slider.component.html',
    styleUrls: ['./image-slider.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class ImageSliderComponent implements OnChanges {
    @Input() @ToJson() images: Image[];

    activeImage: Image;

    ngOnChanges(): void {
        if (this.images && this.images.length) {
            this.images = this.images.slice(0, 3);
            this.setShowCardImage(this.images[0]);
        }
    }

    setShowCardImage(img: Image): void {
        this.activeImage = img;
    }
}
