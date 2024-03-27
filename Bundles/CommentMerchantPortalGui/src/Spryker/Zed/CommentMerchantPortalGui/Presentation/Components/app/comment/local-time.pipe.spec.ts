import { LocalTimePipe } from './local-time.pipe';

Date.prototype.getTimezoneOffset = function () {
    return -120;
};

describe('LocalTimePipe', () => {
    it('should properly transform date for PM period', async () => {
        const time = new LocalTimePipe().transform('2024-03-04 12:11:48.998140');
        expect(time).toBe('14:11:48');
    });

    it('should properly transform date for AM period', async () => {
        const time = new LocalTimePipe().transform('2024-03-04 09:11:48.998140');
        expect(time).toBe('11:11:48');
    });
});
